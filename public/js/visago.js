/* =========================================================
   Visago — interactions & animations
   ========================================================= */
(function () {
    "use strict";

    var prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    var $ = function (selector, scope) {
        return (scope || document).querySelector(selector);
    };
    var $$ = function (selector, scope) {
        return Array.prototype.slice.call((scope || document).querySelectorAll(selector));
    };

    /* ---------------------------------------------------------
       Header: sticky state, mobile menu, dropdowns
       --------------------------------------------------------- */
    var header = $("#siteHeader");
    var nav = $("#primaryNav");
    var navToggle = $("#navToggle");

    var updateHeader = function () {
        if (!header) {
            return;
        }
        header.classList.toggle("is-stuck", window.scrollY > 50);
    };

    updateHeader();
    window.addEventListener("scroll", updateHeader, { passive: true });

    if (navToggle && nav) {
        navToggle.addEventListener("click", function () {
            var open = nav.classList.toggle("is-open");
            navToggle.classList.toggle("is-open", open);
            navToggle.setAttribute("aria-expanded", open ? "true" : "false");
            document.body.classList.toggle("is-locked", open);
        });

        $$(".has-dropdown > a", nav).forEach(function (link) {
            link.addEventListener("click", function (event) {
                if (window.innerWidth > 1200) {
                    return;
                }
                event.preventDefault();
                var parent = link.parentElement;
                var willOpen = !parent.classList.contains("is-open");
                $$(".has-dropdown.is-open", nav).forEach(function (item) {
                    item.classList.remove("is-open");
                });
                parent.classList.toggle("is-open", willOpen);
            });
        });

        $$("a", nav).forEach(function (link) {
            link.addEventListener("click", function () {
                if (window.innerWidth <= 1200 && !link.parentElement.classList.contains("has-dropdown")) {
                    nav.classList.remove("is-open");
                    navToggle.classList.remove("is-open");
                    navToggle.setAttribute("aria-expanded", "false");
                    document.body.classList.remove("is-locked");
                }
            });
        });
    }

    /* ---------------------------------------------------------
       Search panel
       --------------------------------------------------------- */
    var searchToggle = $("#searchToggle");
    var searchPanel = $("#searchPanel");
    var searchClose = $("#searchClose");
    var searchInput = $("#siteSearch");

    var setSearch = function (open) {
        if (!searchPanel || !searchToggle) {
            return;
        }
        searchPanel.classList.toggle("is-open", open);
        searchPanel.setAttribute("aria-hidden", open ? "false" : "true");
        searchToggle.setAttribute("aria-expanded", open ? "true" : "false");
        if (open && searchInput) {
            window.setTimeout(function () {
                searchInput.focus();
            }, 180);
        }
    };

    if (searchToggle) {
        searchToggle.addEventListener("click", function () {
            setSearch(!searchPanel.classList.contains("is-open"));
        });
    }
    if (searchClose) {
        searchClose.addEventListener("click", function () {
            setSearch(false);
        });
    }
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            setSearch(false);
            closeVideo();
        }
    });

    /* ---------------------------------------------------------
       Hero entrance
       --------------------------------------------------------- */
    var hero = $("#hero");
    if (hero) {
        var startHero = function () {
            window.requestAnimationFrame(function () {
                hero.classList.add("hero-ready");
            });
        };
        if (document.readyState === "complete") {
            startHero();
        } else {
            window.addEventListener("load", startHero);
            window.setTimeout(startHero, 900);
        }
    }

    /* ---------------------------------------------------------
       Scroll reveal
       --------------------------------------------------------- */
    var revealEls = $$("[data-anim]");

    if (prefersReducedMotion || !("IntersectionObserver" in window)) {
        revealEls.forEach(function (el) {
            el.classList.add("is-in");
        });
    } else {
        var revealObserver = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("is-in");
                        revealObserver.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.12, rootMargin: "0px 0px -40px 0px" }
        );
        revealEls.forEach(function (el) {
            revealObserver.observe(el);
        });
    }

    /* ---------------------------------------------------------
       Word-by-word heading reveal
       --------------------------------------------------------- */
    var splitTargets = $$(".sec-title, .why-left h2, .fly-copy h2, .about-video-copy h2");

    var splitHeading = function (el) {
        if (el.classList.contains("split") || el.closest(".page-banner") || el.closest(".hero")) {
            return;
        }

        var index = 0;
        var fragment = document.createDocumentFragment();

        Array.prototype.slice.call(el.childNodes).forEach(function (node) {
            if (node.nodeType === 3) {
                node.textContent.split(/(\s+)/).forEach(function (part) {
                    if (!part) {
                        return;
                    }
                    if (/^\s+$/.test(part)) {
                        fragment.appendChild(document.createTextNode(" "));
                        return;
                    }
                    var word = document.createElement("span");
                    word.className = "word";
                    var inner = document.createElement("span");
                    inner.textContent = part;
                    inner.style.setProperty("--i", index);
                    index += 1;
                    word.appendChild(inner);
                    fragment.appendChild(word);
                });
            } else if (node.nodeName === "BR") {
                fragment.appendChild(document.createElement("br"));
            } else {
                fragment.appendChild(node.cloneNode(true));
            }
        });

        el.innerHTML = "";
        el.appendChild(fragment);
        el.classList.add("split");
    };

    if (!prefersReducedMotion) {
        splitTargets.forEach(splitHeading);

        if ("IntersectionObserver" in window) {
            var splitObserver = new IntersectionObserver(
                function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add("is-in");
                            splitObserver.unobserve(entry.target);
                        }
                    });
                },
                { threshold: 0.15 }
            );
            $$(".split").forEach(function (el) {
                splitObserver.observe(el);
            });
        } else {
            $$(".split").forEach(function (el) {
                el.classList.add("is-in");
            });
        }
    }

    /* ---------------------------------------------------------
       Counters
       --------------------------------------------------------- */
    var counters = $$("[data-count]");

    var animateCounter = function (el) {
        var target = parseInt(el.getAttribute("data-count"), 10) || 0;
        var duration = target > 1000 ? 2200 : 1600;
        var start = null;

        var step = function (timestamp) {
            if (start === null) {
                start = timestamp;
            }
            var progress = Math.min((timestamp - start) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            var value = Math.round(eased * target);
            el.textContent = value.toLocaleString("en-US");
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };

        window.requestAnimationFrame(step);
    };

    if (counters.length) {
        if (prefersReducedMotion || !("IntersectionObserver" in window)) {
            counters.forEach(function (el) {
                el.textContent = parseInt(el.getAttribute("data-count"), 10).toLocaleString("en-US");
            });
        } else {
            var counterObserver = new IntersectionObserver(
                function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            animateCounter(entry.target);
                            counterObserver.unobserve(entry.target);
                        }
                    });
                },
                { threshold: 0.55 }
            );
            counters.forEach(function (el) {
                counterObserver.observe(el);
            });
        }
    }

    /* ---------------------------------------------------------
       Skill bars
       --------------------------------------------------------- */
    var skills = $$(".skill-fill");

    if (skills.length) {
        if (prefersReducedMotion || !("IntersectionObserver" in window)) {
            skills.forEach(function (el) {
                el.style.width = el.getAttribute("data-skill") + "%";
            });
        } else {
            var skillObserver = new IntersectionObserver(
                function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            entry.target.style.width = entry.target.getAttribute("data-skill") + "%";
                            skillObserver.unobserve(entry.target);
                        }
                    });
                },
                { threshold: 0.4 }
            );
            skills.forEach(function (el) {
                skillObserver.observe(el);
            });
        }
    }

    /* ---------------------------------------------------------
       Process path
       --------------------------------------------------------- */
    var processTrack = $("#processTrack");
    if (processTrack && "IntersectionObserver" in window) {
        var processObserver = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        processTrack.classList.add("is-in");
                        processObserver.unobserve(processTrack);
                    }
                });
            },
            { threshold: 0.25 }
        );
        processObserver.observe(processTrack);
    } else if (processTrack) {
        processTrack.classList.add("is-in");
    }

    /* ---------------------------------------------------------
       Parallax backgrounds
       --------------------------------------------------------- */
    var parallaxEls = $$("[data-parallax]");

    if (parallaxEls.length && !prefersReducedMotion) {
        var ticking = false;

        var updateParallax = function () {
            var viewport = window.innerHeight;
            parallaxEls.forEach(function (el) {
                var host = el.parentElement;
                var rect = host.getBoundingClientRect();
                if (rect.bottom < -100 || rect.top > viewport + 100) {
                    return;
                }
                var factor = parseFloat(el.getAttribute("data-parallax")) || 0;
                var offset = (rect.top + rect.height / 2 - viewport / 2) * factor;
                el.style.transform = "scale(1.06) translate3d(0," + offset.toFixed(2) + "px,0)";
            });
            ticking = false;
        };

        var requestParallax = function () {
            if (!ticking) {
                window.requestAnimationFrame(updateParallax);
                ticking = true;
            }
        };

        updateParallax();
        window.addEventListener("scroll", requestParallax, { passive: true });
        window.addEventListener("resize", requestParallax);
    }

    /* ---------------------------------------------------------
       Sliders
       --------------------------------------------------------- */
    var sliders = $$("[data-slider]");

    sliders.forEach(function (slider) {
        var track = $(".slider-track", slider);
        if (!track) {
            return;
        }
        var slides = $$(":scope > *", track);
        var dotsWrap = $("[data-slider-dots]", slider);
        var section = slider.closest("section");
        var prevBtn = section ? $("[data-slider-prev]", section) : null;
        var nextBtn = section ? $("[data-slider-next]", section) : null;
        var autoplay = slider.getAttribute("data-autoplay") === "true";
        var index = 0;
        var maxIndex = 0;
        var step = 0;
        var timer = null;

        var measure = function () {
            if (!slides.length) {
                return;
            }
            var first = slides[0];
            var styles = window.getComputedStyle(track);
            var gap = parseFloat(styles.columnGap || styles.gap) || 0;
            step = first.getBoundingClientRect().width + gap;
            var visible = (slider.getBoundingClientRect().width + gap) / step;
            maxIndex = Math.max(0, Math.ceil(slides.length - visible));
            if (index > maxIndex) {
                index = maxIndex;
            }
        };

        var renderDots = function () {
            if (!dotsWrap) {
                return;
            }
            dotsWrap.innerHTML = "";
            if (maxIndex === 0) {
                dotsWrap.style.display = "none";
                return;
            }
            dotsWrap.style.display = "flex";
            for (var i = 0; i <= maxIndex; i += 1) {
                var dot = document.createElement("button");
                dot.type = "button";
                dot.setAttribute("aria-label", "Go to slide " + (i + 1));
                dot.addEventListener("click", function () {
                    index = parseInt(this.getAttribute("data-index"), 10);
                    update();
                    restartAutoplay();
                });
                dot.setAttribute("data-index", i);
                dotsWrap.appendChild(dot);
            }
        };

        var updateDots = function () {
            if (!dotsWrap) {
                return;
            }
            $$("button", dotsWrap).forEach(function (dot, i) {
                dot.classList.toggle("is-active", i === index);
            });
        };

        var apply = function (animate) {
            track.style.transition = animate === false ? "none" : "";
            track.style.transform = "translate3d(-" + (index * step).toFixed(2) + "px,0,0)";
        };

        var update = function () {
            apply();
            updateDots();
        };

        var goTo = function (nextIndex) {
            if (maxIndex === 0) {
                return;
            }
            if (nextIndex < 0) {
                index = maxIndex;
            } else if (nextIndex > maxIndex) {
                index = 0;
            } else {
                index = nextIndex;
            }
            update();
        };

        var startAutoplay = function () {
            if (!autoplay || prefersReducedMotion) {
                return;
            }
            window.clearInterval(timer);
            timer = window.setInterval(function () {
                goTo(index + 1);
            }, 5200);
        };

        var restartAutoplay = function () {
            if (autoplay) {
                startAutoplay();
            }
        };

        if (prevBtn) {
            prevBtn.addEventListener("click", function () {
                goTo(index - 1);
                restartAutoplay();
            });
        }
        if (nextBtn) {
            nextBtn.addEventListener("click", function () {
                goTo(index + 1);
                restartAutoplay();
            });
        }

        slider.addEventListener("mouseenter", function () {
            window.clearInterval(timer);
        });
        slider.addEventListener("mouseleave", startAutoplay);

        /* pointer drag */
        var startX = 0;
        var deltaX = 0;
        var dragging = false;

        track.addEventListener("pointerdown", function (event) {
            if (maxIndex === 0 || event.pointerType === "mouse" && event.button !== 0) {
                return;
            }
            dragging = true;
            startX = event.clientX;
            deltaX = 0;
            track.classList.add("is-dragging");
            track.setPointerCapture(event.pointerId);
            window.clearInterval(timer);
        });

        track.addEventListener("pointermove", function (event) {
            if (!dragging) {
                return;
            }
            deltaX = event.clientX - startX;
            var offset = -index * step + deltaX;
            track.style.transform = "translate3d(" + offset.toFixed(2) + "px,0,0)";
        });

        var endDrag = function () {
            if (!dragging) {
                return;
            }
            dragging = false;
            track.classList.remove("is-dragging");
            if (Math.abs(deltaX) > step * 0.18) {
                if (deltaX < 0) {
                    goTo(index + 1);
                } else {
                    goTo(index - 1);
                }
            } else {
                apply();
            }
            startAutoplay();
        };

        track.addEventListener("pointerup", endDrag);
        track.addEventListener("pointercancel", endDrag);

        var rebuild = function () {
            measure();
            renderDots();
            apply(false);
            updateDots();
        };

        rebuild();
        window.addEventListener("resize", rebuild);
        window.setTimeout(rebuild, 350);
        startAutoplay();
    });

    /* ---------------------------------------------------------
       Video modal
       --------------------------------------------------------- */
    var modal = $("#videoModal");
    var frame = $("#videoFrame");
    var videoUrl = "https://www.youtube.com/embed/hddwAIXbKZo?autoplay=1&rel=0";

    function openVideo() {
        if (!modal || !frame) {
            return;
        }
        frame.innerHTML = '<iframe src="' + videoUrl + '" title="Visago showreel" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        modal.classList.add("is-open");
        modal.setAttribute("aria-hidden", "false");
        document.body.classList.add("is-locked");
    }

    function closeVideo() {
        if (!modal || !frame) {
            return;
        }
        if (!modal.classList.contains("is-open")) {
            return;
        }
        modal.classList.remove("is-open");
        modal.setAttribute("aria-hidden", "true");
        frame.innerHTML = "";
        document.body.classList.remove("is-locked");
    }

    $$("[data-video-open]").forEach(function (btn) {
        btn.addEventListener("click", openVideo);
    });

    $$("[data-video-close]").forEach(function (btn) {
        btn.addEventListener("click", closeVideo);
    });

    /* ---------------------------------------------------------
       Back to top
       --------------------------------------------------------- */
    var backTop = $("#backTop");
    if (backTop) {
        window.addEventListener(
            "scroll",
            function () {
                backTop.classList.toggle("is-visible", window.scrollY > 600);
            },
            { passive: true }
        );
        backTop.addEventListener("click", function () {
            window.scrollTo({ top: 0, behavior: prefersReducedMotion ? "auto" : "smooth" });
        });
    }

    /* ---------------------------------------------------------
       Newsletter / contact demo feedback
       --------------------------------------------------------- */
    $$(".newsletter-form, .contact-form").forEach(function (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();
            var button = form.querySelector('button[type="submit"]');
            if (!button) {
                return;
            }
            var original = button.innerHTML;
            button.innerHTML = '<i class="fa-solid fa-check"></i>';
            button.disabled = true;
            window.setTimeout(function () {
                button.innerHTML = original;
                button.disabled = false;
                form.reset();
            }, 2200);
        });
    });
})();
