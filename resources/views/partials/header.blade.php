<header class="site-header" id="siteHeader">
    <div class="header-inner">
        <a href="{{ route('home') }}" class="brand" aria-label="Visago home">
            <img src="{{ asset('images/visago/logo.png') }}" alt="Visago">
        </a>

        <nav class="primary-nav" id="primaryNav" aria-label="Main navigation">
            <ul>
                <li class="has-dropdown">
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
                    <ul class="dropdown">
                        <li><a href="{{ route('home') }}">Home Layout 1</a></li>
                        <li><a href="{{ route('home') }}#about">About Visago</a></li>
                        <li><a href="{{ route('home') }}#services">Our Services</a></li>
                    </ul>
                </li>
                <li class="has-dropdown">
                    <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'is-active' : '' }}">Pages</a>
                    <ul class="dropdown">
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('home') }}#contact">Contact Us</a></li>
                        <li><a href="{{ route('about') }}#team">Our Team</a></li>
                        <li><a href="{{ route('home') }}#testimonials">Testimonials</a></li>
                        <li><a href="{{ route('home') }}#contact">FAQs</a></li>
                    </ul>
                </li>
                <li class="has-dropdown">
                    <a href="{{ route('home') }}#services">Visa</a>
                    <ul class="dropdown">
                        <li><a href="{{ route('home') }}#services">All Visas</a></li>
                        <li><a href="{{ route('home') }}#services">Visa Details</a></li>
                    </ul>
                </li>
                <li class="has-dropdown">
                    <a href="{{ route('home') }}#countries">Country</a>
                    <ul class="dropdown">
                        <li><a href="{{ route('home') }}#countries">All Countries</a></li>
                        <li><a href="{{ route('home') }}#countries">Country Details</a></li>
                    </ul>
                </li>
                <li class="has-dropdown">
                    <a href="{{ route('home') }}#process">Coaching</a>
                    <ul class="dropdown">
                        <li><a href="{{ route('home') }}#process">Coaching</a></li>
                        <li><a href="{{ route('home') }}#process">Coaching Details</a></li>
                    </ul>
                </li>
                <li class="has-dropdown">
                    <a href="{{ route('home') }}#news">News</a>
                    <ul class="dropdown">
                        <li><a href="{{ route('home') }}#news">News Grid</a></li>
                        <li><a href="{{ route('home') }}#news">News Details</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div class="header-actions">
            <a href="{{ route('home') }}#contact" class="header-icon header-cart" aria-label="View cart">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="cart-count">0</span>
            </a>
            <span class="header-divider" aria-hidden="true"></span>
            <button class="header-icon header-search-toggle" id="searchToggle" aria-label="Open search" aria-expanded="false">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>

        <a href="tel:+0123456789" class="header-phone">
            <span class="header-phone-icon"><i class="fa-solid fa-phone-volume"></i></span>
            <span class="header-phone-copy">
                <small>Call Anytime</small>
                <strong>+012 345 6789</strong>
            </span>
        </a>

        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation" aria-expanded="false" aria-controls="primaryNav">
            <span></span><span></span><span></span>
        </button>
    </div>

    <div class="search-panel" id="searchPanel" aria-hidden="true">
        <form action="{{ route('home') }}" method="GET" role="search">
            <label class="sr-only" for="siteSearch">Search</label>
            <input id="siteSearch" type="search" name="q" placeholder="Search visas, countries, news…" autocomplete="off">
            <button type="submit" aria-label="Search"><i class="fa-solid fa-arrow-right"></i></button>
        </form>
        <button class="search-close" id="searchClose" aria-label="Close search"><i class="fa-solid fa-xmark"></i></button>
    </div>
</header>
