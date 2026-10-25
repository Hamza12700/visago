@extends('layouts.app')

@section('title', 'Visago — Professional Solutions for Visa & Immigrations')
@section('body-class', 'page-home')

@section('content')
    {{-- ============================ HERO ============================ --}}
    <section class="hero" id="hero">
        <div class="hero-bg" data-parallax="0.18" style="background-image: url('{{ asset('images/visago/hero.jpg') }}')"></div>
        <div class="hero-shade"></div>
        <div class="hero-grain"></div>

        <img class="hero-flight" src="{{ asset('images/visago/plane.png') }}" alt="" aria-hidden="true" width="350" height="349">

        <div class="hero-family">
            <img src="{{ asset('images/visago/hero-family.png') }}" alt="Family holding their passports at the airport" width="699" height="750">
        </div>

        <div class="container hero-inner">
            <div class="hero-badge-wrap">
                <span class="hero-badge">Solutions for all type of visas</span>
                <span class="hero-chevrons" aria-hidden="true"><i></i><i></i><i></i></span>
            </div>

            <h1 class="hero-title">
                <span class="hero-line"><span>Professional</span></span>
                <span class="hero-line"><span>Solutions for Visa &amp;</span></span>
                <span class="hero-line"><span>Immigrations</span></span>
            </h1>

            <a href="{{ route('about') }}" class="btn btn-red hero-btn">Request a quote</a>
        </div>

        <a href="#features" class="hero-scroll" aria-label="Scroll to content">
            <span class="hero-scroll-line"></span>
            <span>Scroll</span>
        </a>
    </section>

    {{-- ============================ FEATURES ============================ --}}
    <section class="features" id="features">
        <div class="container">
            <div class="features-grid">
                <article class="feature-card" data-anim="up">
                    <span class="feature-wedge" aria-hidden="true"></span>
                    <span class="feature-icon"><i class="fa-solid fa-shield-halved"></i></span>
                    <div class="feature-copy">
                        <h3>Immigration &amp; Visa Process</h3>
                        <p>We strongly support best practice sharing across</p>
                    </div>
                </article>
                <article class="feature-card" data-anim="up" data-delay="1">
                    <span class="feature-wedge" aria-hidden="true"></span>
                    <span class="feature-icon"><i class="fa-solid fa-passport"></i></span>
                    <div class="feature-copy">
                        <h3>Quick &amp; Easy Visa Application</h3>
                        <p>We strongly support best practice sharing across</p>
                    </div>
                </article>
                <article class="feature-card" data-anim="up" data-delay="2">
                    <span class="feature-wedge" aria-hidden="true"></span>
                    <span class="feature-icon"><i class="fa-solid fa-earth-americas"></i></span>
                    <div class="feature-copy">
                        <h3>Expert &amp; Experince Consultants</h3>
                        <p>We strongly support best practice sharing across</p>
                    </div>
                </article>
            </div>

            <div class="callout" data-anim="up">
                <p>Visago is clearly your best partner at Immigration&amp; Visa success <a href="{{ route('about') }}">Request A Quote</a></p>
                <a href="tel:+5284567592" class="callout-phone">
                    <span class="callout-phone-icon"><i class="fa-solid fa-phone-volume"></i></span>
                    <span>+(528) 456-7592</span>
                </a>
            </div>
        </div>
    </section>

    {{-- ============================ ABOUT ============================ --}}
    <section class="about" id="about">
        <div class="container about-grid">
            <div class="about-visual" data-anim="left">
                <span class="about-bar" aria-hidden="true"></span>
                <figure class="about-photo">
                    <img src="{{ asset('images/visago/about-home.jpg') }}" alt="Traveller holding a passport and boarding pass" width="515" height="600">
                </figure>
                <img class="about-plane" src="{{ asset('images/visago/about-plane.png') }}" alt="" aria-hidden="true">
            </div>

            <div class="about-copy" data-anim="right">
                <span class="sec-label">Who we are</span>
                <h2 class="sec-title">Best Immigration &amp; Visa Consultation.</h2>
                <p class="lead">There are many variations of passages of but the majority have in some form, by injected humou or words which don't look even slightly believable of but the majority have suffered majority have in some variations of passages</p>

                <div class="about-points">
                    <div class="about-point">
                        <span class="dash-icon"><i class="fa-solid fa-passport"></i></span>
                        <div>
                            <h4>Expertise visa<br>Processing</h4>
                        </div>
                    </div>
                    <div class="about-point">
                        <span class="dash-icon"><i class="fa-solid fa-stamp"></i></span>
                        <div>
                            <h4>Fastest Working<br>Process</h4>
                        </div>
                    </div>
                </div>

                <ul class="check-list">
                    <li><i class="fa-solid fa-circle-arrow-right"></i>We strongly support best practice</li>
                    <li><i class="fa-solid fa-circle-arrow-right"></i>Our operations around the world and across</li>
                </ul>

                <a href="{{ route('about') }}" class="btn btn-red">Read More</a>
            </div>
        </div>
    </section>

    {{-- ============================ MARQUEE ============================ --}}
    <div class="marquee" aria-hidden="true">
        <div class="marquee-track">
            @for ($group = 0; $group < 2; $group++)
                @foreach (['Help in Documentation', 'Travel Partners', 'Tours & Travel Agency', 'Visa Processing', 'Immigrations', 'Visa Processing', 'Immigrations'] as $item)
                    <span class="marquee-item">{{ $item }}</span>
                    <span class="marquee-divider"><img src="{{ asset('images/visago/marquee-plane.png') }}" alt=""></span>
                @endforeach
            @endfor
        </div>
    </div>

    {{-- ============================ SERVICES ============================ --}}
    <section class="services" id="services">
        <div class="services-overlay"></div>
        <div class="container">
            <div class="services-head" data-anim="up">
                <span class="sec-label sec-label-light">Service We Provide</span>
                <h2 class="sec-title sec-title-light">Outstanding Immigration<br>Visa Services</h2>
            </div>

            <div class="slider services-slider" data-slider data-autoplay="false">
                <div class="slider-track">
                    @php
                        $services = [
                            ['num' => '01', 'title' => 'Student Visa', 'img' => 'service-student.jpg', 'icon' => 'fa-graduation-cap'],
                            ['num' => '02', 'title' => 'Family Visa', 'img' => 'service-family.jpg', 'icon' => 'fa-people-roof'],
                            ['num' => '03', 'title' => 'Business Visa', 'img' => 'service-business.jpg', 'icon' => 'fa-briefcase'],
                            ['num' => '04', 'title' => 'Working Visa', 'img' => 'service-working.jpg', 'icon' => 'fa-earth-americas'],
                        ];
                    @endphp
                    @foreach ($services as $service)
                        <article class="service-card">
                            <a href="{{ route('home') }}#contact" class="service-media">
                                <img src="{{ asset('images/visago/'.$service['img']) }}" alt="{{ $service['title'] }} consultation" loading="lazy">
                            </a>
                            <div class="service-body">
                                <span class="service-num">{{ $service['num'] }}</span>
                                <div class="service-title-row">
                                    <h3><a href="{{ route('home') }}#contact">{{ $service['title'] }}</a></h3>
                                    <span class="service-icon"><i class="fa-solid {{ $service['icon'] }}"></i></span>
                                </div>
                                <p>We provide a skilled staff to help you get the most out of your immigration.</p>
                                <a href="{{ route('home') }}#contact" class="read-more">Read More <i class="fa-solid fa-angle-right"></i></a>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="slider-dots" data-slider-dots></div>
            </div>
        </div>
    </section>

    {{-- ============================ WHY CHOOSE US ============================ --}}
    <section class="why" id="why">
        <div class="container why-grid">
            <div class="why-left" data-anim="left">
                <div class="why-globe" aria-hidden="true"></div>
                <h2>We ensure prompt services for visa &amp; Immigration</h2>
                <ul class="why-list">
                    <li>
                        <span class="why-icon"><i class="fa-solid fa-gears"></i></span>
                        <div>
                            <h4>Fastest Working Process</h4>
                            <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque</p>
                        </div>
                    </li>
                    <li>
                        <span class="why-icon"><i class="fa-solid fa-passport"></i></span>
                        <div>
                            <h4>Expertise visa Processing</h4>
                            <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque</p>
                        </div>
                    </li>
                    <li>
                        <span class="why-icon"><i class="fa-solid fa-user-shield"></i></span>
                        <div>
                            <h4>Expert Support Panel</h4>
                            <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque</p>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="why-right" data-anim="right">
                <span class="sec-label">why choose us</span>
                <h2 class="sec-title">We are professional<br>Expert in Immigration</h2>
                <p class="lead">There are many variations of passages of available but the majority have suffered alteration in some form, by injected hum randomised words which don't simply free text now slightly.</p>

                <div class="skill">
                    <div class="skill-head"><span>visa consultation</span><span>85%</span></div>
                    <div class="skill-bar"><span class="skill-fill" data-skill="85"></span></div>
                </div>
                <div class="skill">
                    <div class="skill-head"><span>immigration consultancy</span><span>90%</span></div>
                    <div class="skill-bar"><span class="skill-fill" data-skill="90"></span></div>
                </div>

                <div class="why-founder">
                    <span class="why-founder-photo"><img src="{{ asset('images/visago/ceo.jpg') }}" alt="Brittary Herman" loading="lazy"></span>
                    <div>
                        <h4>Brittary Herman</h4>
                        <span>CEO &amp; Founder</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================ COUNTRIES ============================ --}}
    <section class="countries" id="countries">
        <div class="container">
            <div class="countries-grid">
                <div class="countries-head" data-anim="up">
                    <span class="sec-label">Choose Country</span>
                    <h2 class="sec-title">Countries we're support for the immigration</h2>
                </div>

                @php
                    $countries = [
                        ['name' => 'Australia', 'img' => 'country-australia.jpg'],
                        ['name' => 'Germany', 'img' => 'country-germany.jpg'],
                        ['name' => 'Dubai', 'img' => 'country-dubai.png'],
                        ['name' => 'England', 'img' => 'country-england.jpg'],
                        ['name' => 'Portugal', 'img' => 'country-portugal.jpg'],
                        ['name' => 'Spain', 'img' => 'country-spain.jpg'],
                    ];
                @endphp

                @foreach ($countries as $index => $country)
                    <article class="country-card" data-anim="up" data-delay="{{ $index % 3 }}">
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
    </section>

    {{-- ============================ CONTACT ============================ --}}
    <section class="contact" id="contact">
        <div class="contact-skyline" aria-hidden="true"></div>
        <div class="container contact-grid">
            <div class="contact-media" data-anim="left">
                <img src="{{ asset('images/visago/contact.jpg') }}" alt="Couple travelling with their luggage" loading="lazy">
                <div class="contact-ribbons">
                    <span>A full-service</span>
                    <span>Visa Immigration</span>
                    <span>Consluting</span>
                </div>
                <button class="play-btn" data-video-open aria-label="Play the Visago showreel">
                    <span class="play-ring"></span>
                    <i class="fa-solid fa-play"></i>
                </button>
            </div>

            <div class="contact-form-wrap" data-anim="right">
                <span class="sec-label">Contact With Us</span>
                <h2 class="sec-title">Get in Touch for Visa &amp; Immigration</h2>
                <form action="#" method="POST" class="contact-form">
                    @csrf
                    <div class="form-row">
                        <label><span class="sr-only">Your Name</span><input type="text" name="name" placeholder="Your Name" required></label>
                        <label><span class="sr-only">Enter Email</span><input type="email" name="email" placeholder="Enter Email" required></label>
                    </div>
                    <div class="form-row">
                        <label><span class="sr-only">Subject</span><input type="text" name="subject" placeholder="Subject"></label>
                        <label><span class="sr-only">Phone</span><input type="text" name="phone" placeholder="Phone"></label>
                    </div>
                    <label class="form-full"><span class="sr-only">Message</span><textarea name="message" rows="4" placeholder="Message"></textarea></label>
                    <button type="submit" class="btn btn-black">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    {{-- ============================ BRANDS ============================ --}}
    <section class="brands" aria-label="Partners">
        <div class="container brands-row">
            <span class="brand-logo brand-google">google</span>
            <span class="brand-logo brand-amazon">amazon</span>
            <span class="brand-logo brand-spotify"><i class="fa-brands fa-spotify"></i> Spotify</span>
            <span class="brand-logo brand-slack"><i class="fa-brands fa-slack"></i> slack</span>
            <span class="brand-logo brand-amazon">amazon</span>
            <span class="brand-logo brand-google">google</span>
        </div>
    </section>

    {{-- ============================ PROCESS ============================ --}}
    <section class="process" id="process">
        <div class="process-map" aria-hidden="true"></div>
        <img class="process-sparkle process-sparkle-left" src="{{ asset('images/visago/plane-sparkle.png') }}" alt="" aria-hidden="true">
        <img class="process-sparkle process-sparkle-right" src="{{ asset('images/visago/plane-sparkle.png') }}" alt="" aria-hidden="true">

        <div class="container">
            <div class="process-head" data-anim="up">
                <span class="sec-label">Working Proses</span>
                <h2 class="sec-title">Get your visa approved in<br>3 easy simple steps</h2>
            </div>

            <div class="process-track" id="processTrack">
                <svg class="process-path" viewBox="0 0 1160 160" preserveAspectRatio="none" aria-hidden="true">
                    <path id="processPathLine" class="process-path-line" d="M40 120 C 170 20, 330 10, 460 70 C 590 130, 690 130, 790 80 C 900 25, 1040 20, 1130 55" fill="none"/>
                    <path class="process-path-plane" d="M0 10 L26 0 L21 10 L26 20 Z" fill="#df1119">
                        <animateMotion dur="12s" repeatCount="indefinite" rotate="auto">
                            <mpath href="#processPathLine"/>
                        </animateMotion>
                    </path>
                </svg>

                @php
                    $steps = [
                        ['step' => 'step 01', 'title' => 'Complete Online Registrations', 'icon' => 'fa-file-lines'],
                        ['step' => 'step 02', 'title' => 'Documents and Payments', 'icon' => 'fa-file-invoice-dollar'],
                        ['step' => 'step 03', 'title' => 'Receive your Visa Now', 'icon' => 'fa-address-card'],
                    ];
                @endphp

                @foreach ($steps as $index => $step)
                    <div class="process-step" data-anim="up" data-delay="{{ $index }}">
                        <span class="process-circle">
                            <i class="fa-solid {{ $step['icon'] }}"></i>
                            <b><i class="fa-solid fa-check"></i></b>
                        </span>
                        <span class="process-step-tag">{{ $step['step'] }}</span>
                        <h3>{{ $step['title'] }}</h3>
                        <p>These cases are perfectly simple and easy to distinguish.</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================ READY TO FLY ============================ --}}
    <section class="fly">
        <div class="container">
            <div class="fly-banner" data-anim="up">
                <div class="fly-copy">
                    <h2>Ready to fly with us your dream country</h2>
                    <a href="{{ route('home') }}#services" class="fly-link">Our Services <span></span></a>
                </div>
                <div class="fly-traveler" aria-hidden="true">
                    <img src="{{ asset('images/visago/traveler.png') }}" alt="" loading="lazy">
                </div>
                <div class="fly-stats">
                    <div class="fly-stat">
                        <span class="fly-stat-icon"><i class="fa-solid fa-plane-departure"></i></span>
                        <div><strong><span data-count="30">0</span>+</strong><small>Visa Categories</small></div>
                    </div>
                    <div class="fly-stat">
                        <span class="fly-stat-icon"><i class="fa-solid fa-users"></i></span>
                        <div><strong><span data-count="68">0</span>k</strong><small>Visa Process</small></div>
                    </div>
                    <div class="fly-stat">
                        <span class="fly-stat-icon"><i class="fa-solid fa-earth-americas"></i></span>
                        <div><strong><span data-count="99">0</span>+</strong><small>Success Rate</small></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================ TESTIMONIALS ============================ --}}
    <section class="testimonials" id="testimonials">
        <div class="testimonials-map" aria-hidden="true"></div>
        <div class="container testimonials-grid">
            <div class="testimonials-copy" data-anim="left">
                <span class="sec-label sec-label-light">testimonials</span>
                <h2 class="sec-title sec-title-light">What say peoples about us</h2>
                <p>We strongly support best practice sharing across our operations around the world and across various transporation sectors</p>
                <div class="testimonial-nav">
                    <button type="button" class="circle-nav" data-slider-prev aria-label="Previous testimonial"><i class="fa-solid fa-arrow-left"></i></button>
                    <button type="button" class="circle-nav" data-slider-next aria-label="Next testimonial"><i class="fa-solid fa-arrow-right"></i></button>
                </div>
            </div>

            <div class="testimonials-slider" data-anim="right">
                <div class="slider" data-slider data-autoplay="true" data-slider-arrows="outside">
                    <div class="slider-track">
                        @php
                            $testimonials = [
                                ['name' => 'Sarah Albert', 'role' => 'Customers', 'img' => 'client-1.jpg'],
                                ['name' => 'Kevin Martin', 'role' => 'CEO, Buzicon', 'img' => 'client-2.jpg'],
                                ['name' => 'Tina Brown', 'role' => 'Co Founder', 'img' => 'client-3.jpg'],
                                ['name' => 'Mark Wood', 'role' => 'Customers', 'img' => 'client-4.jpg'],
                            ];
                        @endphp
                        @foreach ($testimonials as $testimonial)
                            <article class="testimonial-card">
                                <span class="testimonial-avatar"><img src="{{ asset('images/visago/'.$testimonial['img']) }}" alt="{{ $testimonial['name'] }}" loading="lazy"></span>
                                <h3>Great Service</h3>
                                <div class="stars" aria-label="5 out of 5 stars">
                                    @for ($star = 0; $star < 5; $star++)
                                        <i class="fa-solid fa-star"></i>
                                    @endfor
                                </div>
                                <p>Build and implement innovative, profitable and sustainable products and services that help</p>
                                <h4>{{ $testimonial['name'] }}</h4>
                                <span class="testimonial-role">{{ $testimonial['role'] }}</span>
                            </article>
                        @endforeach
                    </div>
                    <div class="slider-dots" data-slider-dots></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================ NEWS ============================ --}}
    <section class="news" id="news">
        <div class="news-doodle" aria-hidden="true"></div>
        <div class="container">
            <div class="news-head" data-anim="up">
                <span class="sec-label">News &amp; Blog</span>
                <h2 class="sec-title">Latest News from insight</h2>
            </div>

            <div class="news-grid">
                @php
                    $posts = [
                        ['img' => 'blog-1.jpg', 'day' => '12', 'month' => 'Feb', 'title' => 'Citizenship Concept on How to Become a UK Resident'],
                        ['img' => 'blog-2.jpg', 'day' => '12', 'month' => 'Feb', 'title' => 'How consultation is affecting ventures in 2024'],
                        ['img' => 'blog-3.jpg', 'day' => '12', 'month' => 'Feb', 'title' => 'Project Concepts or Related Queries Should be'],
                    ];
                @endphp

                @foreach ($posts as $index => $post)
                    <article class="news-card" data-anim="up" data-delay="{{ $index }}">
                        <a href="#" class="news-media">
                            <img src="{{ asset('images/visago/'.$post['img']) }}" alt="{{ $post['title'] }}" loading="lazy">
                            <span class="news-date"><b>{{ $post['day'] }}</b><i>{{ $post['month'] }}, 2024</i></span>
                        </a>
                        <div class="news-body">
                            <div class="news-meta">
                                <span><i class="fa-regular fa-user"></i> By <a href="#">ismail</a></span>
                                <span><i class="fa-regular fa-comments"></i> No Comments</span>
                            </div>
                            <h3><a href="#">{{ $post['title'] }}</a></h3>
                            <a href="#" class="read-more">Read More <i class="fa-solid fa-circle-arrow-right"></i></a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
