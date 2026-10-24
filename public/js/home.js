/* =========================================================
   Visago homepage interactions
   ========================================================= */
(function () {
    'use strict';

    /* Render everything at once when ?static=1 is used (full-page screenshots) */
    var staticMode = window.location.search.indexOf('static') !== -1;

    /* ---------- sticky header ---------- */
    var header = document.getElementById('siteHeader');
    var onScroll = function () {
        header.classList.toggle('scrolled', window.scrollY > 60);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    /* ---------- mobile nav ---------- */
    var toggle = document.getElementById('navToggle');
    var nav = document.getElementById('mainNav');
    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            nav.classList.toggle('open');
            toggle.innerHTML = nav.classList.contains('open')
                ? '<i class="fa-solid fa-xmark"></i>'
                : '<i class="fa-solid fa-bars"></i>';
        });
    }

    /* ---------- scroll reveal ---------- */
    var revealEls = document.querySelectorAll('[data-reveal]');
    if (staticMode) {
        revealEls.forEach(function (el) { el.classList.add('in'); });
    } else if ('IntersectionObserver' in window) {
        var revealObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.16, rootMargin: '0px 0px -40px 0px' });

        revealEls.forEach(function (el) { revealObserver.observe(el); });
    } else {
        revealEls.forEach(function (el) { el.classList.add('in'); });
    }

    /* ---------- animated counters (stats) ---------- */
    var counters = document.querySelectorAll('.stat-num');
    if (staticMode) {
        counters.forEach(function (el) { el.textContent = el.dataset.count; });
    } else if ('IntersectionObserver' in window) {
        var countObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) { return; }
                var el = entry.target;
                var target = parseInt(el.dataset.count, 10) || 0;
                var duration = 1800;
                var start = null;

                var step = function (ts) {
                    if (!start) { start = ts; }
                    var progress = Math.min((ts - start) / duration, 1);
                    // ease-out cubic for a natural finish
                    var eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.round(eased * target);
                    if (progress < 1) { requestAnimationFrame(step); }
                };
                requestAnimationFrame(step);
                countObserver.unobserve(el);
            });
        }, { threshold: 0.6 });
        counters.forEach(function (el) { countObserver.observe(el); });
    }

    /* ---------- skill bars (why choose us) ---------- */
    var skills = document.querySelectorAll('.skill-fill');
    if (staticMode) {
        skills.forEach(function (el) { el.style.width = el.dataset.value + '%'; });
    } else if ('IntersectionObserver' in window) {
        var skillObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.style.width = entry.target.dataset.value + '%';
                    skillObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        skills.forEach(function (el) { skillObserver.observe(el); });
    }

    /* ---------- dashed flight path draw-in ---------- */
    var processTrack = document.querySelector('.process-track');
    if (processTrack) {
        var processObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    processTrack.classList.add('in');
                    processObserver.unobserve(processTrack);
                }
            });
        }, { threshold: 0.3 });
        processObserver.observe(processTrack);
    }

    /* ---------- testimonials slider ---------- */
    var track = document.getElementById('testiTrack');
    var prev = document.getElementById('testiPrev');
    var next = document.getElementById('testiNext');
    if (track && prev && next) {
        var index = 0;

        var maxIndex = function () {
            var visible = window.innerWidth > 1180
                ? (window.innerWidth > 992 ? 2 : 1)
                : 1;
            return Math.max(0, track.children.length - visible);
        };

        var update = function () {
            var card = track.children[0];
            var gap = 30;
            var shift = card.getBoundingClientRect().width + gap;
            track.style.transform = 'translateX(' + (-index * shift) + 'px)';
        };

        prev.addEventListener('click', function () {
            index = index > 0 ? index - 1 : maxIndex();
            update();
        });
        next.addEventListener('click', function () {
            index = index < maxIndex() ? index + 1 : 0;
            update();
        });
        window.addEventListener('resize', function () {
            if (index > maxIndex()) { index = maxIndex(); }
            update();
        });
    }
})();
