@extends('layouts.app')

@section('title', 'About — Visago')
@section('meta-description', 'Learn about Visago — a trusted immigration and visa consultation agency helping people start new chapters abroad.')
@section('body-class', 'page-about')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
@endpush

@section('content')
    {{-- ============================ PAGE BANNER ============================ --}}
    <section class="page-banner">
        <div class="page-banner-bg" data-parallax="0.12" style="background-image: url('{{ asset('images/visago/hero.jpg') }}')"></div>
        <div class="page-banner-shade"></div>
        <div class="container page-banner-inner">
            <h1>About</h1>
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                <span>About</span>
            </nav>
        </div>
    </section>

    {{-- ============================ WHO WE ARE ============================ --}}
    <section class="about-intro" id="about">
        <div class="container about-intro-grid">
            <div class="about-collage" data-anim="left">
                <span class="about-collage-bar" aria-hidden="true"></span>
                <figure class="collage-main">
                    <img src="{{ asset('images/visago/about-main.jpg') }}" alt="Happy travellers at the airport" width="480" height="638">
                </figure>
                <figure class="collage-secondary">
                    <img src="{{ asset('images/visago/about-2.jpg') }}" alt="Traveller holding a passport" width="317" height="472">
                </figure>
                <div class="collage-stat">
                    <span class="collage-stat-icon">
                        <img src="{{ asset('images/visago/about-3.jpg') }}" alt="" aria-hidden="true">
                    </span>
                    <div>
                        <strong><span data-count="32600">0</span>+</strong>
                        <small>Clients Satisfied</small>
                    </div>
                </div>
            </div>

            <div class="about-intro-copy" data-anim="right">
                <span class="sec-label">Who we are</span>
                <h2 class="sec-title">Best Immigration &amp; Visa Consultation.</h2>
                <p class="lead">There are many variations of passages of but the majority have in some form, by injected humou or words which don't look even slightly believable of but the majority have suffered majority have in some variations of passages</p>

                <div class="about-intro-body">
                    <ul class="about-service-list">
                        <li>
                            <span class="dash-icon"><i class="fa-solid fa-passport"></i></span>
                            <div>
                                <h4>Visa Consultation</h4>
                                <p>There are many variations of passages of but the majority have in some form</p>
                            </div>
                        </li>
                        <li>
                            <span class="dash-icon"><i class="fa-solid fa-people-group"></i></span>
                            <div>
                                <h4>Professional Services</h4>
                                <p>There are many variations of passages of but the majority have in some form</p>
                            </div>
                        </li>
                    </ul>

                    <aside class="about-side-card">
                        <h4>Ready to fly with us your dream country</h4>
                        <a href="{{ route('home') }}#contact">More <i class="fa-solid fa-arrow-right"></i></a>
                    </aside>
                </div>

                <a href="{{ route('home') }}#services" class="btn btn-black">Discover More</a>
            </div>
        </div>
    </section>

    {{-- ============================ SERVICES ============================ --}}
    <section class="about-services" id="services">
        <div class="about-services-red" aria-hidden="true"></div>
        <div class="about-services-globe" aria-hidden="true"></div>

        <div class="container">
            <div class="about-services-head" data-anim="up">
                <span class="sec-label">SERVICE WE PROVIDE</span>
                <h2 class="sec-title">Outstanding immigration visa services</h2>
            </div>

            <div class="slider about-services-slider" data-slider data-autoplay="false">
                <div class="slider-track">
                    @php
                        $services = [
                            ['title' => 'Student Visa', 'img' => 'service-student.jpg', 'icon' => 'fa-graduation-cap'],
                            ['title' => 'Family Visa', 'img' => 'service-family.jpg', 'icon' => 'fa-people-roof'],
                            ['title' => 'Business Visa', 'img' => 'service-business.jpg', 'icon' => 'fa-briefcase'],
                            ['title' => 'Working Visa', 'img' => 'service-working-alt.jpg', 'icon' => 'fa-earth-americas'],
                        ];
                    @endphp

                    @foreach ($services as $service)
                        <article class="about-service-card">
                            <a href="{{ route('home') }}#contact" class="about-service-media">
                                <img src="{{ asset('images/visago/'.$service['img']) }}" alt="{{ $service['title'] }} consultation" loading="lazy">
                            </a>
                            <div class="about-service-body">
                                <span class="about-service-icon"><i class="fa-solid {{ $service['icon'] }}"></i></span>
                                <span class="about-service-chevrons" aria-hidden="true"><i></i><i></i><i></i></span>
                                <h3><a href="{{ route('home') }}#contact">{{ $service['title'] }}</a></h3>
                                <p>We provide a skilled staff to help you get the most out of your immigration.</p>
                            </div>
                            <a href="{{ route('home') }}#contact" class="about-service-more">Read More <i class="fa-solid fa-arrow-right"></i></a>
                        </article>
                    @endforeach
                </div>
                <div class="slider-dots" data-slider-dots></div>
            </div>
        </div>
    </section>

    {{-- ============================ VIDEO ============================ --}}
    <section class="about-video">
        <div class="about-video-bg" data-parallax="0.08"></div>
        <div class="about-video-shade"></div>
        <div class="about-video-red" aria-hidden="true"></div>

        <div class="container about-video-inner">
            <div class="about-video-copy" data-anim="left">
                <span class="about-video-bar" aria-hidden="true"></span>
                <h2>Most Trusted Immigration &amp; Visa Solutions Agency</h2>
            </div>

            <div class="about-video-action" data-anim="right">
                <span class="watch-video-label">Watch Video</span>
                <svg class="watch-video-arrow" viewBox="0 0 120 60" fill="none" aria-hidden="true">
                    <path d="M4 50 C 42 48, 78 30, 112 14" stroke="#ffffff" stroke-width="1.5" stroke-dasharray="5 5"/>
                    <path d="M103 9 L114 14 L105 22" stroke="#ffffff" stroke-width="1.5" fill="none"/>
                </svg>
                <button class="video-play" data-video-open aria-label="Play the Visago showreel">
                    <span class="video-play-ring"></span>
                    <i class="fa-solid fa-play"></i>
                </button>
            </div>
        </div>
    </section>

    {{-- ============================ COUNTRIES ============================ --}}
    <section class="about-countries" id="countries">
        <div class="container about-countries-grid">
            <div class="about-countries-visual" data-anim="left">
                <img src="{{ asset('images/visago/passport.png') }}" alt="Passport and travel documents" loading="lazy">
            </div>

            <div class="about-countries-copy">
                <div class="about-countries-head" data-anim="up">
                    <span class="sec-label">Choose Country</span>
                    <h2 class="sec-title">Immigration Choose Your Country</h2>
                </div>

                <div class="about-countries-cards">
                    @php
                        $countries = [
                            ['name' => 'Australia', 'img' => 'country-australia.jpg'],
                            ['name' => 'Germany', 'img' => 'country-germany-2.png'],
                            ['name' => 'England', 'img' => 'country-england-2.png'],
                            ['name' => 'Dubai', 'img' => 'country-dubai.png'],
                        ];
                    @endphp

                    @foreach ($countries as $index => $country)
                        <article class="country-card" data-anim="up" data-delay="{{ $index % 2 }}">
                            <a href="{{ route('home') }}#contact" class="country-arrow" aria-label="Explore {{ $country['name'] }}"><i class="fa-solid fa-arrow-right"></i></a>
                            <div class="country-head">
                                <span class="country-flag"><img src="{{ asset('images/visago/'.$country['img']) }}" alt="{{ $country['name'] }} flag" loading="lazy"></span>
                                <h3>{{ $country['name'] }}</h3>
                            </div>
                            <hr>
                            <p>We denou righteous indigna beguiled demoralized</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ============================ TEAM ============================ --}}
    <section class="about-team" id="team">
        <div class="container">
            <div class="about-team-head" data-anim="up">
                <span class="sec-label">Our Team</span>
                <h2 class="sec-title">Experience Team Members</h2>
            </div>

            <div class="team-grid">
                @php
                    $team = [
                        ['name' => 'Prince Brown', 'role' => 'Senior Consultant', 'img' => 'team-1.jpg'],
                        ['name' => 'Sara Albert', 'role' => 'Senior Consultant', 'img' => 'team-2.jpg'],
                        ['name' => 'Stock William', 'role' => 'Support Engineer', 'img' => 'team-3.jpg'],
                    ];
                @endphp

                @foreach ($team as $index => $member)
                    <article class="team-card" data-anim="up" data-delay="{{ $index }}">
                        <div class="team-photo">
                            <img src="{{ asset('images/visago/'.$member['img']) }}" alt="{{ $member['name'] }}" loading="lazy">
                        </div>
                        <div class="team-info">
                            <span class="team-accent" aria-hidden="true"></span>
                            <div>
                                <h3>{{ $member['name'] }}</h3>
                                <span>{{ $member['role'] }}</span>
                            </div>
                            <a href="#" class="team-share" aria-label="Share {{ $member['name'] }}'s profile"><i class="fa-solid fa-share-nodes"></i></a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
