#!/usr/bin/env python3
"""
Scrape every image and SVG asset referenced by the pages of a site.

Default run (crawls https://dev24.kodesolution.com/visago/):

    python3 scripts/scrape_assets.py

Output tree:

    <output>/images/<name>                     images (flat, original file names)
    <output>/svg/<name>                        standalone .svg files
    <output>/svg/inline/<page>-<n>-<hash>.svg  inline <svg> blocks, verbatim
    <output>/manifest.json                     every asset, where it was found, status

What is collected:

    * <img>, <picture>/<source>, <video poster>, <embed>, <object>, <input type="image">
    * every srcset candidate, plus lazy-load attributes (data-src, data-lazy-src, ...)
    * CSS background images: url() in style="" attributes, <style> blocks and .css files
    * image URLs embedded in inline <script> blocks (sliders, galleries, JSON configs)
    * inline <svg>...</svg> markup, and <use href="sprite.svg#id"> references
    * data:image/... base64 payloads (optional, on by default)
    * every image in the WordPress media library, used or not (--wp-media)

Favicons (.ico) are skipped by default; change that with --skip-ext.

Crawling stays on the base host (and, by default, under the base path), honours
robots.txt, rate limits requests and deduplicates both URLs and bytes. Pages are
fetched in parallel (--page-workers) and downloads start as soon as an asset is
found, so files land on disk while the crawl is still running.
"""

from __future__ import annotations

import argparse
import base64
import concurrent.futures as cf
import hashlib
import html as html_lib
import json
import posixpath
import re
import sys
import threading
import time
from collections import deque
from dataclasses import asdict, dataclass
from datetime import datetime, timezone
from html.parser import HTMLParser
from pathlib import Path
from typing import Iterator
from urllib.parse import unquote, urljoin, urlsplit, urlunsplit
from urllib.robotparser import RobotFileParser

try:
    import requests
except ImportError:  # pragma: no cover - dependency guard
    sys.exit("This script needs the 'requests' package: python3 -m pip install requests")


DEFAULT_BASE_URL = "https://dev24.kodesolution.com/visago/"
DEFAULT_USER_AGENT = "VisagoAssetScraper/1.0 (asset inventory; +https://dev24.kodesolution.com/visago/)"
MANIFEST_NAME = "manifest.json"
MAX_ASSET_BYTES = 64 * 1024 * 1024
QUIET = False

PAGE_ACCEPT = "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8"
IMAGE_ACCEPT = "image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8"
SHEET_ACCEPT = "text/css,*/*;q=0.1"

SRCSET_ATTRS = ("srcset", "data-srcset", "data-lazy-srcset", "imagesrcset")
NON_URL_ATTRS = {
    "alt",
    "title",
    "class",
    "id",
    "rel",
    "type",
    "name",
    "width",
    "height",
    "loading",
    "decoding",
    "sizes",
    "role",
    "target",
    "media",
    "integrity",
    "crossorigin",
    "referrerpolicy",
    "fetchpriority",
    "tabindex",
    "method",
    "action",
    "value",
    "placeholder",
    "for",
    "lang",
    "dir",
    "datetime",
    "colspan",
    "rowspan",
    "scope",
    "data-lib",
    "data-libid",
    "data-id",
    "data-index",
    "data-slideindex",
}
TAG_ASSET_ATTRS = {
    "img": ("src", "data-src", "data-lazy-src", "data-original", "data-fallback-src", "data-image"),
    "source": ("src", "data-src"),
    "video": ("poster", "src", "data-poster"),
    "audio": ("src",),
    "input": ("src",),
    "embed": ("src",),
    "object": ("data",),
    "use": ("href", "xlink:href"),
    "image": ("href", "xlink:href"),
}
# Attributes whose value is a URL even when it has no image file extension.
CERTAIN_ATTRS = {attr for attrs in TAG_ASSET_ATTRS.values() for attr in attrs}

IMAGE_EXT_RE = re.compile(r"\.(?:jpe?g|png|gif|webp|avif|bmp|ico|tiff?|svg)$", re.I)
IMAGE_EXT_ANY_RE = re.compile(r"\.(?:jpe?g|png|gif|webp|avif|bmp|ico|tiff?|svg)(?=[?#\"'\s),]|$)", re.I)
CSS_URL_RE = re.compile(r"url\(\s*(?P<q>['\"]?)(?P<url>.+?)(?P=q)\s*\)", re.I | re.S)
ABS_URL_RE = re.compile(r"(?:https?:)?//[^\s\"'<>()\\]+", re.I)
REL_URL_RE = re.compile(r"(?<![\w:/.@%-])/[^\s\"'<>()\\]+")
BARE_PATH_RE = re.compile(
    r"(?<![\w/.-])(?:\.{1,2}/)?[\w@%+~-]+(?:/[\w.@%+~-]+)*"
    r"\.(?:jpe?g|png|gif|webp|avif|bmp|ico|tiff?|svg)(?:\?[\w=&%.+-]*)?",
    re.I,
)
SVG_BLOCK_RE = re.compile(r"<svg\b[^>]*>.*?</svg\s*>", re.I | re.S)
LOC_RE = re.compile(r"<loc>\s*([^<\s]+)\s*</loc>", re.I)
DATA_URI_RE = re.compile(r"^data:(?P<meta>[^,]*),(?P<data>.*)$", re.S | re.I)
HTML_START = (b"<!doctype html", b"<html")

CONTENT_TYPE_EXTS = {
    "image/jpeg": "jpg",
    "image/jpg": "jpg",
    "image/pjpeg": "jpg",
    "image/png": "png",
    "image/gif": "gif",
    "image/webp": "webp",
    "image/avif": "avif",
    "image/svg+xml": "svg",
    "image/bmp": "bmp",
    "image/x-icon": "ico",
    "image/vnd.microsoft.icon": "ico",
    "image/tiff": "tif",
}

DEFAULT_EXCLUDES = (
    r"/wp-admin(?:/|$)",
    r"wp-login\.php",
    r"wp-json",
    r"xmlrpc\.php",
    r"/feed/?$",
    r"/comments/feed",
    r"replytocom=",
    r"add-to-cart=",
    r"/cart/?$",
    r"/checkout(?:/|$)",
    r"/my-account(?:/|$)",
    r"\?s=",
)


# --------------------------------------------------------------------------- #
# Small URL / text helpers
# --------------------------------------------------------------------------- #


def log(message: str) -> None:
    if not QUIET:
        print(message, file=sys.stderr, flush=True)


def normalize_url(raw: str, base: str) -> str | None:
    """Resolve a possibly relative, escaped or protocol-relative URL."""
    if not raw:
        return None
    value = html_lib.unescape(raw.strip().strip("'\"")).replace("\\/", "/").strip()
    if not value or value.startswith(("#", "javascript:", "mailto:", "tel:", "data:")):
        return None
    try:
        absolute = urljoin(base, value)
        parts = urlsplit(absolute)
    except ValueError:
        return None
    if parts.scheme not in ("http", "https") or not parts.netloc:
        return None
    return urlunsplit((parts.scheme, parts.netloc.lower(), parts.path or "/", parts.query, ""))


def parse_srcset(value: str) -> Iterator[str]:
    """Yield every candidate URL from a srcset attribute."""
    for candidate in value.split(","):
        url = candidate.strip().split(" ")[0].strip()
        if url and not url.startswith("data:"):
            yield url


def iter_urls_in_text(text: str) -> Iterator[str]:
    """Yield URL-ish strings found in CSS, inline styles or script payloads."""
    text = html_lib.unescape(text or "").replace("\\/", "/")
    if not text:
        return

    found: list[str] = []
    for match in CSS_URL_RE.finditer(text):
        found.append(match.group("url").strip())

    rest = CSS_URL_RE.sub(" ", text)
    found.extend(match.group(0) for match in ABS_URL_RE.finditer(rest))
    rest = ABS_URL_RE.sub(" ", rest)
    found.extend(match.group(0) for match in REL_URL_RE.finditer(rest))
    rest = REL_URL_RE.sub(" ", rest)
    found.extend(match.group(0) for match in BARE_PATH_RE.finditer(rest))

    seen: set[str] = set()
    for url in found:
        url = url.strip().strip(",;'\"").strip()
        if url and url not in seen:
            seen.add(url)
            yield url


def asset_kind(url: str) -> str:
    return "svg" if urlsplit(url).path.lower().endswith(".svg") else "image"


def sanitize(name: str) -> str:
    cleaned = re.sub(r"[^A-Za-z0-9._-]+", "_", name).strip("._")
    return cleaned or "file"


def slugify_page(url: str, limit: int = 70) -> str:
    path = urlsplit(url).path.strip("/").replace("/", "-") or "index"
    slug = re.sub(r"[^A-Za-z0-9._-]+", "-", path).strip("-")
    return (slug[-limit:] or "page").strip("-")


def sniff_extension(data: bytes, content_type: str | None) -> str | None:
    """Detect the image type from magic bytes, falling back to the header."""
    head = data[:1024]
    if head.startswith(b"\x89PNG\r\n\x1a\n"):
        return "png"
    if head.startswith(b"\xff\xd8\xff"):
        return "jpg"
    if head.startswith((b"GIF87a", b"GIF89a")):
        return "gif"
    if head[:4] == b"RIFF" and head[8:12] == b"WEBP":
        return "webp"
    if head[4:8] == b"ftyp" and head[8:12] in (b"avif", b"avis"):
        return "avif"
    if head.startswith(b"BM"):
        return "bmp"
    if head.startswith(b"\x00\x00\x01\x00"):
        return "ico"
    if head.startswith((b"II*\x00", b"MM\x00*")):
        return "tif"

    lowered = head.lower()
    if b"<svg" in lowered:
        return "svg"

    ctype = (content_type or "").split(";")[0].strip().lower()
    return CONTENT_TYPE_EXTS.get(ctype)


def is_html_payload(data: bytes) -> bool:
    head = data[:512].lstrip().lower()
    return head.startswith(HTML_START)


def decode_base64_text(value: str) -> str | None:
    """Decode base64-encoded attributes such as Elementor's data-dbsrc."""
    candidate = value.strip()
    if len(candidate) < 24 or not re.fullmatch(r"[A-Za-z0-9+/=_-]+", candidate):
        return None
    padded = candidate + "=" * (-len(candidate) % 4)
    try:
        decoded = base64.b64decode(padded.replace("-", "+").replace("_", "/"), validate=True)
        text = decoded.decode("utf-8")
    except (ValueError, TypeError, UnicodeDecodeError):
        return None
    if not text or len(text) > 4096:
        return None
    return text if IMAGE_EXT_ANY_RE.search(text) or "url(" in text else None


# --------------------------------------------------------------------------- #
# HTML parsing
# --------------------------------------------------------------------------- #


class AssetHTMLParser(HTMLParser):
    """Collect links, asset candidates, CSS and inline script payloads."""

    def __init__(self, page_url: str) -> None:
        super().__init__(convert_charrefs=True)
        self.page_url = page_url
        self.links: list[str] = []
        self.css_links: list[str] = []
        # (raw url, how it was found, url is certainly an asset)
        self.assets: list[tuple[str, str, bool]] = []
        self.style_texts: list[str] = []
        self.script_texts: list[str] = []
        self._in_style = False
        self._in_script = False
        self._chunks: list[str] = []

    # HTMLParser hooks ----------------------------------------------------- #

    def handle_starttag(self, tag: str, attrs: list[tuple[str, str | None]]) -> None:
        self._handle_tag(tag, attrs)

    def handle_startendtag(self, tag: str, attrs: list[tuple[str, str | None]]) -> None:
        self._handle_tag(tag, attrs)

    def handle_endtag(self, tag: str) -> None:
        if tag == "style" and self._in_style:
            self._in_style = False
            self.style_texts.append("".join(self._chunks))
            self._chunks = []
        elif tag == "script" and self._in_script:
            self._in_script = False
            self.script_texts.append("".join(self._chunks))
            self._chunks = []

    def handle_data(self, data: str) -> None:
        if self._in_style or self._in_script:
            self._chunks.append(data)

    # Internals ------------------------------------------------------------ #

    def _handle_tag(self, tag: str, attrs: list[tuple[str, str | None]]) -> None:
        tag = tag.lower()
        values = {name.lower(): (value or "") for name, value in attrs}

        if tag == "style":
            self._in_style = True
            self._chunks = []
        elif tag == "script":
            self._in_script = True
            self._chunks = []

        if tag == "a" and values.get("href"):
            self.links.append(values["href"])

        if tag == "link":
            href = values.get("href", "")
            rel = values.get("rel", "").lower()
            if "stylesheet" in rel and href:
                self.css_links.append(href)
            if href and any(token in rel for token in ("icon", "image_src", "mask-icon", "preload", "prefetch")):
                if IMAGE_EXT_ANY_RE.search(href) or "icon" in rel:
                    self.assets.append((href, f"link[rel={rel}]", True))

        for attr in TAG_ASSET_ATTRS.get(tag, ()):
            value = values.get(attr)
            if not value:
                continue
            if tag in ("video", "audio", "source") and not IMAGE_EXT_ANY_RE.search(value):
                if not (tag == "video" and attr == "poster"):
                    continue
            self.assets.append((value, f"{tag}[{attr}]", attr in CERTAIN_ATTRS))

        for attr in SRCSET_ATTRS:
            value = values.get(attr)
            if value:
                for candidate in parse_srcset(value):
                    self.assets.append((candidate, f"{tag}[{attr}]", True))

        # Catch-all: lazy-load attributes, Elementor data-bg, inline styles, meta images.
        for name, value in values.items():
            if not value or name in SRCSET_ATTRS or name in NON_URL_ATTRS:
                continue
            if name in TAG_ASSET_ATTRS.get(tag, ()):
                continue
            candidates = [value]
            decoded = decode_base64_text(value)
            if decoded:
                candidates.append(decoded)
            for candidate in candidates:
                for url in iter_urls_in_text(candidate):
                    self.assets.append((url, f"{tag}[{name}]", bool(IMAGE_EXT_ANY_RE.search(url))))


# --------------------------------------------------------------------------- #
# Data model
# --------------------------------------------------------------------------- #


@dataclass
class Asset:
    url: str
    kind: str
    found_on: str
    via: str = ""
    file: str | None = None
    content_type: str | None = None
    bytes: int = 0
    sha256: str | None = None
    status: str = "pending"
    error: str | None = None
    duplicate_of: str | None = None
    seen_on_pages: int = 1


# --------------------------------------------------------------------------- #
# Scraper
# --------------------------------------------------------------------------- #


class RateLimiter:
    """Space request starts by a minimum interval, shared across threads."""

    def __init__(self, interval: float) -> None:
        self.interval = max(0.0, interval)
        self._lock = threading.Lock()
        self._next = 0.0

    def wait(self) -> None:
        if self.interval <= 0:
            return
        with self._lock:
            now = time.monotonic()
            start = max(now, self._next)
            self._next = start + self.interval
        pause = start - now
        if pause > 0:
            time.sleep(pause)


class Scraper:
    def __init__(self, args: argparse.Namespace) -> None:
        self.args = args
        self.base_url = normalize_url(args.base_url, args.base_url) or args.base_url
        if not urlsplit(self.base_url).path.endswith("/") and not posixpath.splitext(urlsplit(self.base_url).path)[1]:
            self.base_url = f"{self.base_url}/"
        self.output = Path(args.output).expanduser().resolve()
        self.origin = "{0.scheme}://{0.netloc}".format(urlsplit(self.base_url))

        self.assets: dict[str, Asset] = {}
        self.pages: list[dict] = []
        self.visited: set[str] = set()
        self.queued: set[str] = set()
        self.written: dict[str, str] = {}  # sha256 -> relative path
        self.robots: RobotFileParser | None = None
        self.excludes = [re.compile(pattern, re.I) for pattern in (*DEFAULT_EXCLUDES, *args.exclude)]
        self.skip_extensions = {
            item.strip().lstrip(".").lower() for item in (args.skip_ext or "").split(",") if item.strip()
        }
        self.skipped_by_extension: dict[str, int] = {}
        self._skipped_seen: set[str] = set()
        self._css_seen: set[str] = set()

        self._local = threading.local()
        self._lock = threading.RLock()
        self._page_limit = RateLimiter(args.delay)
        self._download_limit = RateLimiter(args.download_delay)
        self._download_pool: cf.ThreadPoolExecutor | None = None
        self._download_futures: list[cf.Future] = []
        self._submitted: set[str] = set()
        self._downloads_done = 0

    # HTTP ------------------------------------------------------------------ #

    def session(self) -> requests.Session:
        session = getattr(self._local, "session", None)
        if session is None:
            session = requests.Session()
            session.headers.update({"User-Agent": self.args.user_agent, "Accept-Language": "en-US,en;q=0.9"})
            adapter = requests.adapters.HTTPAdapter(pool_maxsize=self.args.workers + 4)
            session.mount("http://", adapter)
            session.mount("https://", adapter)
            self._local.session = session
        return session

    def fetch(
        self,
        url: str,
        accept: str,
        referer: str | None = None,
        retries: int | None = None,
    ) -> tuple[requests.Response | None, str | None]:
        headers = {"Accept": accept}
        if referer:
            headers["Referer"] = referer
        attempts = retries or self.args.retries

        for attempt in range(1, attempts + 1):
            try:
                response = self.session().get(
                    url,
                    headers=headers,
                    timeout=self.args.timeout,
                    allow_redirects=True,
                )
            except requests.RequestException as exc:
                if attempt == attempts:
                    return None, f"{type(exc).__name__}: {exc}"
                time.sleep(min(2 ** attempt * 0.5, 8))
                continue

            if response.status_code in (429, 500, 502, 503, 504) and attempt < attempts:
                time.sleep(min(2 ** attempt * 0.5, 8))
                continue
            return response, None

        return None, "request failed"

    def allowed(self, url: str) -> bool:
        if self.robots is None:
            return True
        try:
            return self.robots.can_fetch(self.args.user_agent, url)
        except Exception:  # noqa: BLE001 - robotparser is fragile with odd input
            return True

    def setup_robots(self) -> None:
        if self.args.ignore_robots:
            return
        robots_url = urljoin(self.origin, "/robots.txt")
        parser = RobotFileParser(robots_url)
        response, _ = self.fetch(robots_url, "text/plain,*/*", retries=1)
        if response is not None and response.ok:
            parser.parse(response.text.splitlines())
        else:
            parser.parse([])
        self.robots = parser

    # Scope ----------------------------------------------------------------- #

    def in_scope(self, url: str) -> bool:
        parts, base = urlsplit(url), urlsplit(self.base_url)
        if parts.netloc != base.netloc:
            return False
        if self.args.scope == "host":
            return True
        base_path = base.path if base.path.endswith("/") else f"{base.path}/"
        return parts.path == base.path.rstrip("/") or parts.path.startswith(base_path)

    def excluded(self, url: str) -> bool:
        return any(pattern.search(url) for pattern in self.excludes)

    def seed_urls(self) -> list[str]:
        seeds: list[str] = []
        for candidate in (self.base_url, *self.args.page):
            normalized = normalize_url(candidate, self.base_url)
            if normalized and normalized not in seeds:
                seeds.append(normalized)
        if self.args.sitemap:
            for candidate in self.seed_from_sitemap():
                if candidate not in seeds:
                    seeds.append(candidate)
        return seeds

    def seed_from_sitemap(self) -> list[str]:
        queue = [
            urljoin(self.base_url, name)
            for name in ("wp-sitemap.xml", "sitemap_index.xml", "sitemap.xml")
        ]
        seen: set[str] = set()
        pages: list[str] = []
        while queue and len(pages) < 5000 and len(seen) < 100:
            url = queue.pop(0)
            if url in seen:
                continue
            seen.add(url)
            response, _ = self.fetch(url, "application/xml,text/xml,*/*", retries=1)
            if response is None or not response.ok:
                continue
            for loc in LOC_RE.findall(response.text):
                loc = html_lib.unescape(loc)
                if loc.endswith(".xml"):
                    queue.append(loc)
                else:
                    pages.append(loc)
            time.sleep(self.args.delay / 2)
        log(f"  sitemap: {len(pages)} page URLs")
        return pages

    def collect_wp_media(self) -> None:
        """Enumerate the WordPress media library, including unused uploads."""
        endpoint = urljoin(self.base_url, "wp-json/wp/v2/media")
        page = 1
        total_pages = 1
        while page <= total_pages and page <= 200:
            query = f"{endpoint}?per_page=100&page={page}&_fields=source_url,mime_type"
            response, error = self.fetch(query, "application/json,*/*", retries=1)
            if response is None or not response.ok:
                status = "no response" if response is None else f"http-{response.status_code}"
                log(f"  media library: {error or status}; skipping")
                return
            total_pages = int(response.headers.get("X-WP-TotalPages") or 1)
            try:
                items = response.json()
            except ValueError:
                return
            if not isinstance(items, list):
                return
            for item in items:
                if not isinstance(item, dict):
                    continue
                source = item.get("source_url")
                mime = str(item.get("mime_type") or "")
                if not source:
                    continue
                if mime.startswith("image/") or source.lower().endswith(".svg"):
                    self.add_asset(source, self.base_url, "wp-json media", certain=True)
            log(f"  media library: page {page}/{total_pages}")
            page += 1
            time.sleep(self.args.delay / 2)

    # Crawling -------------------------------------------------------------- #

    def crawl(self) -> None:
        frontier: deque[str] = deque()
        for url in self.seed_urls():
            self.enqueue(frontier, url)

        pages_done = 0
        with cf.ThreadPoolExecutor(max_workers=self.args.page_workers) as pool:
            while frontier:
                if self.args.max_pages and pages_done >= self.args.max_pages:
                    log(f"Reached --max-pages ({self.args.max_pages}); stopping crawl.")
                    break

                batch: list[str] = []
                while frontier and len(batch) < self.args.page_workers:
                    url = frontier.popleft()
                    if url in self.visited:
                        continue
                    self.visited.add(url)
                    batch.append(url)
                if self.args.max_pages:
                    batch = batch[: max(0, self.args.max_pages - pages_done)]
                if not batch:
                    continue

                pages_done += len(batch)
                start = pages_done - len(batch)
                for offset, url in enumerate(batch, start=1):
                    log(f"[page {start + offset}] {url}")

                for links in pool.map(self.crawl_page, batch):
                    for link in links:
                        self.enqueue(frontier, link)

    def enqueue(self, frontier: deque[str], url: str) -> None:
        if url in self.queued or url in self.visited:
            return
        if not self.in_scope(url) or self.excluded(url):
            return
        self.queued.add(url)
        if not self.allowed(url):
            with self._lock:
                self.pages.append({"url": url, "status": "blocked-by-robots", "assets": 0})
            return
        frontier.append(url)

    def crawl_page(self, url: str) -> list[str]:
        try:
            record, links = self._crawl_page(url)
        except Exception as exc:  # noqa: BLE001 - one bad page must not stop the crawl
            record, links = {"url": url, "status": "error", "error": f"{type(exc).__name__}: {exc}", "assets": 0}, []
        with self._lock:
            self.pages.append(record)
        return links

    def _crawl_page(self, url: str) -> tuple[dict, list[str]]:
        self._page_limit.wait()
        response, error = self.fetch(url, PAGE_ACCEPT)
        if response is None:
            return {"url": url, "status": "error", "error": error, "assets": 0}, []
        if not response.ok:
            return {"url": url, "status": f"http-{response.status_code}", "assets": 0}, []

        content_type = response.headers.get("content-type", "")
        if "html" not in content_type.lower() and not response.content.lstrip().lower().startswith(b"<"):
            return {"url": url, "status": "not-html", "assets": 0}, []

        text = response.text
        parser = AssetHTMLParser(url)
        parse_error: str | None = None
        try:
            parser.feed(text)
        except Exception as exc:  # noqa: BLE001 - malformed markup should not abort a run
            parse_error = str(exc)

        found_here = self.absorb_page(url, text, parser)

        links: list[str] = []
        for link in parser.links:
            target = normalize_url(link, url)
            if target:
                links.append(target)

        return {"url": url, "status": "ok", "assets": found_here, "warning": parse_error}, links

    def absorb_page(self, page_url: str, html_text: str, parser: AssetHTMLParser) -> int:
        before = len(self.assets)

        for raw, via, certain in parser.assets:
            self.add_asset(raw, page_url, via, certain)

        for css_text in parser.style_texts:
            for raw in iter_urls_in_text(css_text):
                self.add_asset(raw, page_url, "style[]", bool(IMAGE_EXT_ANY_RE.search(raw)))

        if self.args.css:
            for href in dict.fromkeys(parser.css_links):
                css_url = normalize_url(href, page_url)
                if css_url and self.allowed(css_url):
                    self.collect_css_assets(css_url, page_url)

        if self.args.inline_js:
            for script_text in parser.script_texts:
                for raw in iter_urls_in_text(script_text):
                    self.add_asset(raw, page_url, "script[]", bool(IMAGE_EXT_ANY_RE.search(raw)))

        self.extract_inline_svgs(page_url, html_text)
        return len(self.assets) - before

    def collect_css_assets(self, css_url: str, page_url: str) -> None:
        with self._lock:
            if css_url in self._css_seen:
                return
            self._css_seen.add(css_url)

        try:
            response, _ = self.fetch(css_url, SHEET_ACCEPT, referer=page_url, retries=1)
        except Exception:  # noqa: BLE001
            return
        if response is None or not response.ok:
            return
        if len(response.content) > 5 * 1024 * 1024:
            return
        for raw in iter_urls_in_text(response.text):
            self.add_asset(raw, page_url, "css url()", bool(IMAGE_EXT_ANY_RE.search(raw)), base=css_url)

    def extract_inline_svgs(self, page_url: str, html_text: str) -> None:
        cleaned = re.sub(r"<!--.*?-->", "", html_text, flags=re.S)
        slug = slugify_page(page_url)
        for index, match in enumerate(SVG_BLOCK_RE.finditer(cleaned), start=1):
            # Skip markup that only looks like an <svg> because it sits inside an
            # attribute value, e.g. a data: URI in a style="..." attribute.
            prefix = cleaned[: match.start()]
            if prefix.rfind("<") > prefix.rfind(">"):
                continue
            svg = match.group(0)
            if len(svg) < 32:
                continue
            digest = hashlib.sha256(svg.encode("utf-8")).hexdigest()
            url = f"{page_url}#inline-svg-{index}"
            asset = self.assets.get(url)
            if asset is None:
                asset = Asset(url=url, kind="inline-svg", found_on=page_url, via="<svg>")
                self.assets[url] = asset
            self.save_inline_svg(asset, svg.encode("utf-8"), digest, f"{slug}-{index:02d}")

    def skip(self, extension: str, key: str | None = None) -> bool:
        if extension not in self.skip_extensions:
            return False
        with self._lock:
            if key is not None:
                if key in self._skipped_seen:
                    return True
                self._skipped_seen.add(key)
            self.skipped_by_extension[extension] = self.skipped_by_extension.get(extension, 0) + 1
        return True

    # Asset registration ---------------------------------------------------- #

    def add_asset(self, raw: str, page_url: str, via: str, certain: bool, base: str | None = None) -> None:
        if not raw:
            return
        raw = raw.strip()
        if raw.lower().startswith("data:"):
            if self.args.data_uris:
                self.add_data_uri(raw, page_url, via)
            return

        url = normalize_url(raw, base or page_url)
        if url is None:
            return
        if not certain and not IMAGE_EXT_ANY_RE.search(urlsplit(url).path):
            return
        if self.skip(posixpath.splitext(urlsplit(url).path)[1].lstrip(".").lower(), url):
            return

        kind = asset_kind(url)
        with self._lock:
            existing = self.assets.get(url)
            if existing is not None:
                existing.seen_on_pages += 1
                return
            asset = Asset(url=url, kind=kind, found_on=page_url, via=via)
            self.assets[url] = asset
        self.submit_download(asset)

    def add_data_uri(self, raw: str, page_url: str, via: str) -> None:
        match = DATA_URI_RE.match(raw)
        if not match:
            return
        meta = match.group("meta")
        mime = (meta.split(";")[0].strip() or "application/octet-stream").lower()
        if not mime.startswith("image/") and "svg" not in mime:
            return
        if self.skip(CONTENT_TYPE_EXTS.get(mime, "")):
            return
        is_base64 = any(part.strip().lower() == "base64" for part in meta.split(";")[1:])
        payload = match.group("data")
        try:
            data = base64.b64decode(payload, validate=False) if is_base64 else unquote(payload).encode("utf-8")
        except (ValueError, TypeError):
            return

        digest = hashlib.sha256(data).hexdigest()
        url = f"data:{mime};sha256:{digest[:16]}"
        with self._lock:
            if url in self.assets:
                return
            self.assets[url] = Asset(url=url, kind="svg" if "svg" in mime else "image", found_on=page_url, via=via)

        asset = self.assets[url]
        extension = sniff_extension(data, mime)
        if extension is None:
            asset.status = "unsupported"
            return
        self.save_bytes(asset, data, extension, name_hint="inline")

    # Downloading ----------------------------------------------------------- #

    def start_downloads(self) -> None:
        if self.args.dry_run:
            return
        self._download_pool = cf.ThreadPoolExecutor(max_workers=self.args.workers)
        log(f"Downloading with {self.args.workers} worker(s) while crawling...")

    def submit_download(self, asset: Asset) -> None:
        """Queue an asset as soon as it is discovered, so files appear immediately."""
        if self._download_pool is None or asset.kind == "inline-svg":
            return
        with self._lock:
            if asset.url in self._submitted:
                return
            self._submitted.add(asset.url)
            future = self._download_pool.submit(self.download_one, asset)
            self._download_futures.append(future)

    def finish_downloads(self, cancel: bool = False) -> None:
        pool, self._download_pool = self._download_pool, None
        if pool is None:
            return
        if cancel:
            for future in list(self._download_futures):
                future.cancel()
        pool.shutdown(wait=True)
        with self._lock:
            for asset in self.assets.values():
                if asset.status == "pending" and asset.kind != "inline-svg":
                    asset.status = "cancelled"
        log(f"Download phase finished: {self._downloads_done} asset(s) processed")

    def download_one(self, asset: Asset) -> None:
        try:
            self._download_asset(asset)
        except Exception as exc:  # noqa: BLE001 - a single bad asset must not kill the run
            asset.status = "error"
            asset.error = f"{type(exc).__name__}: {exc}"
        finally:
            with self._lock:
                self._downloads_done += 1
                done = self._downloads_done
            if done % 25 == 0:
                log(f"  {done} asset(s) processed")

    def _download_asset(self, asset: Asset) -> None:
        extension = posixpath.splitext(urlsplit(asset.url).path)[1].lstrip(".").lower()
        if extension in self.skip_extensions:
            asset.status = "skipped"
            return

        self._download_limit.wait()

        response, error = self.fetch(asset.url, IMAGE_ACCEPT, referer=asset.found_on)
        if response is None:
            asset.status = "error"
            asset.error = error or "request failed"
            return
        if response.status_code == 404:
            asset.status = "missing"
            return
        if not response.ok:
            asset.status = f"http-{response.status_code}"
            return

        data = response.content
        asset.content_type = response.headers.get("content-type", "").split(";")[0].strip() or None
        if not data:
            asset.status = "empty"
            return
        if len(data) > MAX_ASSET_BYTES:
            asset.status = "too-large"
            return
        if is_html_payload(data):
            asset.status = "not-an-image"
            return

        extension = sniff_extension(data, asset.content_type)
        if extension is None and asset.url.lower().endswith(".svg") and b"<svg" in data[:2048].lower():
            extension = "svg"
        if extension is None:
            asset.status = "unsupported"
            return

        self.save_bytes(asset, data, extension)

    def save_inline_svg(self, asset: Asset, data: bytes, digest: str, name_hint: str) -> None:
        with self._lock:
            if self.args.dedupe and digest in self.written:
                asset.status = "duplicate"
                asset.duplicate_of = self.written[digest]
                asset.file = self.written[digest]
                asset.sha256 = digest
                asset.bytes = len(data)
                return
            relative = Path("svg") / "inline" / f"{sanitize(name_hint)}-{digest[:8]}.svg"
            self._store(asset, data, relative, digest)

    def save_bytes(self, asset: Asset, data: bytes, extension: str, name_hint: str | None = None) -> None:
        digest = hashlib.sha256(data).hexdigest()
        with self._lock:
            if self.args.dedupe:
                existing = self.written.get(digest)
                if existing:
                    asset.status = "duplicate"
                    asset.duplicate_of = existing
                    asset.file = existing
                    asset.sha256 = digest
                    asset.bytes = len(data)
                    return

            relative = self.target_path(asset, extension, digest, name_hint)
            self._store(asset, data, relative, digest)

    def _store(self, asset: Asset, data: bytes, relative: Path, digest: str) -> None:
        destination = self.output / relative

        if destination.exists() and not self.args.overwrite:
            same = destination.stat().st_size == len(data) and hashlib.sha256(destination.read_bytes()).hexdigest() == digest
            if not same:
                relative = relative.with_name(f"{relative.stem}-{digest[:8]}{relative.suffix}")
                destination = self.output / relative

        if destination.exists() and not self.args.overwrite:
            asset.status = "cached"
        elif self.args.dry_run:
            asset.status = "dry-run"
        else:
            destination.parent.mkdir(parents=True, exist_ok=True)
            temporary = destination.with_suffix(f"{destination.suffix}.part")
            temporary.write_bytes(data)
            temporary.replace(destination)
            asset.status = "downloaded"

        asset.file = relative.as_posix()
        asset.bytes = len(data)
        asset.sha256 = digest
        with self._lock:
            self.written.setdefault(digest, relative.as_posix())

    def target_path(self, asset: Asset, extension: str, digest: str, name_hint: str | None) -> Path:
        if name_hint:
            root = Path("svg") / "inline" if extension == "svg" else Path("images")
            return root / f"{sanitize(name_hint)}-{digest[:8]}.{extension}"

        parts = urlsplit(asset.url)
        remote_dir = unquote(posixpath.dirname(parts.path))
        name = unquote(posixpath.basename(parts.path)) or "index"

        if name.endswith("/"):
            name = "index"
        stem, current = posixpath.splitext(name)
        current_ext = current.lstrip(".").lower()
        if not current or current_ext in {"php", "html", "htm", "ashx", "aspx", "cgi", "jsp"}:
            name = f"{sanitize(stem) or 'asset'}.{extension}"
        elif current_ext != extension and not (current_ext in {"jpeg"} and extension == "jpg"):
            name = f"{sanitize(stem)}.{extension}"
        else:
            name = sanitize(name)

        if not self.args.mirror:
            root = Path("svg") if extension == "svg" else Path("images")
            return root / name

        root = "svg/external" if extension == "svg" else "images"
        directories = [sanitize(segment) for segment in remote_dir.split("/") if segment]
        return Path(root) / sanitize(parts.netloc) / Path(*directories) / name

    # Output ---------------------------------------------------------------- #

    def load_existing_manifest(self) -> None:
        path = self.output / MANIFEST_NAME
        if not path.exists():
            return
        try:
            data = json.loads(path.read_text(encoding="utf-8"))
        except (OSError, ValueError):
            return
        known = set(Asset.__dataclass_fields__)
        for item in data.get("assets", []):
            if not isinstance(item, dict) or not item.get("url"):
                continue
            try:
                asset = Asset(**{key: item[key] for key in item if key in known})
            except TypeError:
                continue
            if asset.file and (self.output / asset.file).exists():
                asset.status = "cached"
            self.assets.setdefault(asset.url, asset)
        log(f"Resumed from manifest: {len(self.assets)} known asset(s)")

    def write_manifest(self) -> Path:
        self.output.mkdir(parents=True, exist_ok=True)
        assets = sorted(self.assets.values(), key=lambda item: item.url)
        summary = {
            "pages_crawled": sum(1 for page in self.pages if page.get("status") == "ok"),
            "assets_found": len(assets),
            "images": sum(1 for item in assets if item.kind == "image"),
            "svg_files": sum(1 for item in assets if item.kind == "svg"),
            "inline_svgs": sum(1 for item in assets if item.kind == "inline-svg"),
            "downloaded": sum(1 for item in assets if item.status == "downloaded"),
            "cached": sum(1 for item in assets if item.status == "cached"),
            "duplicates": sum(1 for item in assets if item.status == "duplicate"),
            "skipped_by_extension": self.skipped_by_extension,
            "errors": sum(
                1
                for item in assets
                if item.status not in ("downloaded", "cached", "duplicate", "pending", "dry-run", "skipped")
            ),
        }
        payload = {
            "base_url": self.base_url,
            "generated_at": datetime.now(timezone.utc).isoformat(timespec="seconds"),
            "summary": summary,
            "pages": sorted(self.pages, key=lambda page: page.get("url", "")),
            "assets": [asdict(item) for item in assets],
        }
        path = self.output / MANIFEST_NAME
        path.write_text(json.dumps(payload, indent=2, ensure_ascii=False), encoding="utf-8")
        return path

    def print_summary(self) -> None:
        assets = list(self.assets.values())
        images = sum(1 for item in assets if item.kind == "image")
        svg_files = sum(1 for item in assets if item.kind == "svg")
        inline = sum(1 for item in assets if item.kind == "inline-svg")
        downloaded = sum(1 for item in assets if item.status == "downloaded")
        cached = sum(1 for item in assets if item.status == "cached")
        duplicates = sum(1 for item in assets if item.status == "duplicate")
        problems = [item for item in assets if item.status not in ("downloaded", "cached", "duplicate", "pending", "dry-run", "skipped")]

        print("")
        print(f"Pages crawled : {sum(1 for page in self.pages if page.get('status') == 'ok')}")
        print(f"Assets found  : {len(assets)} (images {images}, svg files {svg_files}, inline svg {inline})")
        print(f"Saved         : {downloaded} new, {cached} already on disk, {duplicates} duplicate")
        print(f"Problems      : {len(problems)}")
        if self.skipped_by_extension:
            skipped = ", ".join(f"{count} .{ext}" for ext, count in sorted(self.skipped_by_extension.items()))
            print(f"Skipped       : {sum(self.skipped_by_extension.values())} ({skipped})")
        print(f"Output        : {self.output}")
        print(f"Manifest      : {self.output / MANIFEST_NAME}")
        if problems:
            print("")
            print("First problems:")
            for asset in problems[:15]:
                print(f"  {asset.status:<16} {asset.url}  {asset.error or ''}")

    def run(self) -> None:
        self.output.mkdir(parents=True, exist_ok=True)
        self.setup_robots()
        if self.args.resume:
            self.load_existing_manifest()

        self.start_downloads()
        try:
            self.crawl()
            if self.args.wp_media:
                log("Enumerating WordPress media library...")
                self.collect_wp_media()
        except BaseException:
            self.finish_downloads(cancel=True)
            raise

        self.finish_downloads()
        if self.args.dry_run:
            log("Dry run: assets resolved but not downloaded.")
        manifest = self.write_manifest()
        log(f"Manifest written to {manifest}")
        self.print_summary()


# --------------------------------------------------------------------------- #
# CLI
# --------------------------------------------------------------------------- #


def build_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(
        description="Scrape every image and SVG referenced by a site.",
        formatter_class=argparse.ArgumentDefaultsHelpFormatter,
    )
    parser.add_argument("--base-url", default=DEFAULT_BASE_URL, help="Start URL, also defines the crawl scope.")
    parser.add_argument("--page", action="append", default=[], metavar="URL", help="Extra seed page (repeatable).")
    parser.add_argument("-o", "--output", default="scraped-assets", help="Output directory.")
    parser.add_argument("--max-pages", type=int, default=0, help="Stop after N HTML pages (0 = unlimited).")
    parser.add_argument("--scope", choices=("path", "host"), default="path", help="Crawl under the base path or the whole host.")
    parser.add_argument("--exclude", action="append", default=[], metavar="REGEX", help="Extra URL patterns to skip (repeatable).")
    parser.add_argument("--sitemap", action="store_true", help="Also seed pages from wp-sitemap.xml / sitemap.xml.")
    parser.add_argument("--wp-media", action="store_true", help="Also collect images from the WordPress media library API.")
    parser.add_argument("--delay", type=float, default=0.3, help="Minimum seconds between any two page requests.")
    parser.add_argument("--page-workers", type=int, default=5, help="Pages fetched in parallel.")
    parser.add_argument(
        "--download-delay",
        type=float,
        default=0.02,
        help="Minimum seconds between any two asset downloads.",
    )
    parser.add_argument("--workers", type=int, default=12, help="Parallel download workers.")
    parser.add_argument(
        "--skip-ext",
        default="ico",
        metavar="EXTS",
        help="Comma-separated file extensions to never download (empty string downloads everything).",
    )
    parser.add_argument("--timeout", type=float, default=30.0, help="Per-request timeout in seconds.")
    parser.add_argument("--retries", type=int, default=3, help="Attempts per request.")
    parser.add_argument("--user-agent", default=DEFAULT_USER_AGENT, help="User-Agent header to send.")
    parser.add_argument("--css", action=argparse.BooleanOptionalAction, default=True, help="Fetch stylesheets and read url() backgrounds.")
    parser.add_argument("--inline-js", dest="inline_js", action=argparse.BooleanOptionalAction, default=True, help="Scan inline scripts for image URLs.")
    parser.add_argument("--data-uris", dest="data_uris", action=argparse.BooleanOptionalAction, default=True, help="Save base64 data: image payloads.")
    parser.add_argument("--dedupe", action=argparse.BooleanOptionalAction, default=True, help="Skip files whose bytes were already saved.")
    parser.add_argument(
        "--mirror",
        action="store_true",
        help="Mirror the remote host/path structure instead of the default flat images/ + svg/ layout.",
    )
    parser.add_argument("--overwrite", action="store_true", help="Overwrite existing files instead of keeping them.")
    parser.add_argument("--resume", action="store_true", help="Merge with an existing manifest and skip saved files.")
    parser.add_argument("--dry-run", action="store_true", help="Discover assets without downloading them.")
    parser.add_argument("--ignore-robots", action="store_true", help="Do not consult robots.txt.")
    parser.add_argument("-q", "--quiet", action="store_true", help="Only print the final summary.")
    return parser


def main(argv: list[str] | None = None) -> int:
    global QUIET

    parser = build_parser()
    args = parser.parse_args(argv)
    if args.page_workers < 1 or args.workers < 1:
        parser.error("--page-workers and --workers must be at least 1")
    QUIET = args.quiet

    scraper = Scraper(args)
    try:
        scraper.run()
    except KeyboardInterrupt:
        log("\nInterrupted. Writing manifest for what was found so far...")
        scraper.write_manifest()
        scraper.print_summary()
        return 130
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
