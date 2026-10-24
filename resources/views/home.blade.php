<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Visago — professional visa & immigration consulting services.">
    <title>Visago | Visa & Immigration Consulting</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>

    <!-- ======================= HEADER ======================= -->
    <header class="site-header" id="siteHeader">
        <a href="#" class="logo">VISAGO</a>
        <nav class="main-nav" id="mainNav">
            <a href="#" class="active">Home</a>
            <a href="#about">Pages</a>
            <a href="#services">Visa</a>
            <a href="#countries">Country</a>
            <a href="#process">Coaching</a>
            <a href="#news">News</a>
        </nav>
        <button class="nav-toggle" id="navToggle" aria-label="Menu"><i class="fa-solid fa-bars"></i></button>
    </header>

    <!-- ======================= HERO ======================= -->
    <section class="hero">
        <div class="hero-media"><img src="{{ asset('images/hero-bg.jpg') }}" alt=""></div>
        <div class="hero-travelers" data-reveal="right">
            <img src="{{ asset('images/family.png') }}" alt="Family holding passports and travel documents">
        </div>
        <div class="container hero-inner">
            <div class="hero-badge" data-reveal>
                <span>Solutions for all type of visas</span>
                <i class="fa-solid fa-angles-right"></i><i class="fa-solid fa-angles-right"></i>
            </div>
            <h1 class="hero-title" data-reveal>Professional<br>Solutions for Visa &amp;<br>Immigrations</h1>
            <a href="#contact" class="btn btn-red" data-reveal>Request A Quote</a>
        </div>
        <img class="hero-flight" src="{{ asset('images/plane.png') }}" alt="" aria-hidden="true">
    </section>

    <!-- ======================= FEATURE CARDS ======================= -->
    <section class="features">
        <div class="container features-grid">
            <article class="feature-card" data-reveal>
                <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <div>
                    <h3>Immigration &amp; Visa Process</h3>
                    <p>Expert guidance through every step of your visa journey.</p>
                </div>
            </article>
            <article class="feature-card" data-reveal data-delay="1">
                <div class="feature-icon"><i class="fa-solid fa-passport"></i></div>
                <div>
                    <h3>Quick &amp; Easy Visa Application</h3>
                    <p>Streamlined paperwork so your approval comes faster.</p>
                </div>
            </article>
            <article class="feature-card" data-reveal data-delay="2">
                <div class="feature-icon"><i class="fa-solid fa-globe"></i></div>
                <div>
                    <h3>Expert &amp; Experience Consultants</h3>
                    <p>Certified advisors who know immigration law inside out.</p>
                </div>
            </article>
        </div>

        <div class="container callout-wrap">
            <div class="callout" data-reveal>
                <div class="callout-left">
                    <p>Visago is your best partner for Immigration &amp; Visa success <a href="#contact">Request A Quote</a></p>
                </div>
                <a href="tel:+15284567592" class="callout-phone">
                    <span class="callout-ring"><i class="fa-solid fa-phone"></i></span>
                    <span class="callout-number">+(528) 456-7592</span>
                </a>
            </div>
        </div>
    </section>

    <!-- ======================= WHO WE ARE ======================= -->
    <section class="about" id="about">
        <div class="container about-grid">
            <div class="about-media" data-reveal="left">
                <img src="{{ asset('images/about.jpg') }}" alt="Traveler holding passport and tickets">
            </div>
            <div class="about-content" data-reveal="right">
                <div class="sec-label chevron-top">Who We Are</div>
                <p class="about-text">There are many different routes that lead people abroad — study, work, family or a brand-new start. Whatever your reason, our consultants map out the clearest path, prepare the strongest file and stay with you until your visa is in your hands.</p>
                <div class="about-points">
                    <div class="about-point">
                        <span class="dash-circle"><i class="fa-solid fa-plane-departure"></i></span>
                        <h4>Expertise visa<br>Processing</h4>
                    </div>
                    <div class="about-point">
                        <span class="dash-circle"><i class="fa-solid fa-passport"></i></span>
                        <h4>Fastest Working<br>Process</h4>
                    </div>
                </div>
                <ul class="check-list">
                    <li><i class="fa-solid fa-circle-arrow-right"></i> We stand by the best practices of the industry</li>
                    <li><i class="fa-solid fa-circle-arrow-right"></i> Our operations span the whole world and more</li>
                </ul>
                <a href="#services" class="btn btn-red">Read More</a>
            </div>
        </div>
    </section>

    <!-- ======================= MARQUEE STRIP ======================= -->
    <div class="marquee">
        <div class="marquee-track">
            @foreach (array_merge($items = ['Tours & Travel Agency', 'Visa Processing', 'Immigrations'], $items, $items) as $item)
                <span class="marquee-item">{{ $item }} <i class="fa-solid fa-plane"></i></span>
            @endforeach
        </div>
    </div>

    <!-- ======================= SERVICES ======================= -->
    <section class="services" id="services">
        <div class="container">
            <div class="sec-label sec-label-light chevron-top-center" data-reveal>Service We Provide</div>
            <div class="services-grid">
                <article class="service-card" data-reveal>
                    <div class="service-thumb">
                        <img src="{{ asset('images/svc-family.jpg') }}" alt="Family visa consultation">
                    </div>
                    <div class="service-panel">
                        <div class="service-panel-text">
                            <span class="service-num">01</span>
                            <h3>Family Visa</h3>
                        </div>
                        <span class="dash-circle service-icon"><i class="fa-solid fa-people-roof"></i></span>
                    </div>
                    <div class="service-body">
                        <p>We provide a skilled team to help you bring the people you love under one roof — wherever they are.</p>
                        <hr>
                        <a href="#contact" class="link-more">Read More <i class="fa-solid fa-angles-right"></i></a>
                    </div>
                </article>
                <article class="service-card" data-reveal data-delay="1">
                    <div class="service-thumb">
                        <img src="{{ asset('images/svc-business.jpg') }}" alt="Business visa consultation">
                    </div>
                    <div class="service-panel">
                        <div class="service-panel-text">
                            <span class="service-num">02</span>
                            <h3>Business Visa</h3>
                        </div>
                        <span class="dash-circle service-icon"><i class="fa-solid fa-briefcase"></i></span>
                    </div>
                    <div class="service-body">
                        <p>From investor routes to conference trips, we get your paperwork flying as fast as your plans.</p>
                        <hr>
                        <a href="#contact" class="link-more">Read More <i class="fa-solid fa-angles-right"></i></a>
                    </div>
                </article>
                <article class="service-card" data-reveal data-delay="2">
                    <div class="service-thumb">
                        <img src="{{ asset('images/svc-working.jpg') }}" alt="Working visa documents">
                    </div>
                    <div class="service-panel">
                        <div class="service-panel-text">
                            <span class="service-num">03</span>
                            <h3>Working Visa</h3>
                        </div>
                        <span class="dash-circle service-icon"><i class="fa-solid fa-earth-americas"></i></span>
                    </div>
                    <div class="service-body">
                        <p>Skilled-worker, sponsorship and permit routes handled by advisers who file them every day.</p>
                        <hr>
                        <a href="#contact" class="link-more">Read More <i class="fa-solid fa-angles-right"></i></a>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- ======================= WHY CHOOSE US ======================= -->
    <section class="why">
        <div class="why-left" data-reveal="left">
            <div class="why-globe"></div>
            <div class="container-left">
                <div class="why-item">
                    <span class="dash-circle dash-circle-light"><i class="fa-solid fa-shield-halved"></i></span>
                    <div>
                        <h4>Fastest Working Process</h4>
                        <p>Applications reviewed and lodged the very same week you sign up with us.</p>
                    </div>
                </div>
                <div class="why-item">
                    <span class="dash-circle dash-circle-light"><i class="fa-solid fa-earth-americas"></i></span>
                    <div>
                        <h4>Expertise visa Processing</h4>
                        <p>Decades of combined experience across the toughest case types.</p>
                    </div>
                </div>
                <div class="why-item">
                    <span class="dash-circle dash-circle-light"><i class="fa-solid fa-users-viewfinder"></i></span>
                    <div>
                        <h4>Expert Support Panel</h4>
                        <p>Direct access to registered migration agents whenever you need answers.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="why-right" data-reveal="right">
            <div class="sec-label chevron-top">Why Choose Us</div>
            <p class="why-text">A visa is more than a stamp — it is the first page of a new chapter. Our team reviews every case personally, spots the weak points before the embassy does and gives you an honest, fixed plan from day one.</p>
            <div class="skill">
                <div class="skill-head"><span>Visa Consultation</span><span class="skill-val">85%</span></div>
                <div class="skill-bar"><span class="skill-fill" data-value="85"></span></div>
            </div>
            <div class="skill">
                <div class="skill-head"><span>Immigration Consultancy</span><span class="skill-val">90%</span></div>
                <div class="skill-bar"><span class="skill-fill" data-value="90"></span></div>
            </div>
            <div class="why-person">
                <img src="{{ asset('images/avatar-2.jpg') }}" alt="Portrait of the agency founder">
                <div>
                    <h4>Brittary Herman</h4>
                    <span>CEO &amp; Founder</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= COUNTRIES ======================= -->
    <section class="countries" id="countries">
        <div class="container">
            <div class="countries-grid">
                <div class="sec-label chevron-top countries-label" data-reveal>Choose Country</div>
                <article class="country-card" data-reveal><a href="#contact" class="country-arrow"><i class="fa-solid fa-arrow-right"></i></a>
                    <div class="country-head"><img src="https://flagcdn.com/w80/au.png" alt="Flag of Australia"><h3>Australia</h3></div>
                    <p>Skilled, study and partner streams tailored to your profile.</p>
                </article>
                <article class="country-card" data-reveal data-delay="1"><a href="#contact" class="country-arrow"><i class="fa-solid fa-arrow-right"></i></a>
                    <div class="country-head"><img src="https://flagcdn.com/w80/de.png" alt="Flag of Germany"><h3>Germany</h3></div>
                    <p>Blue Card and job-seeker routes for EU newcomers.</p>
                </article>
                <article class="country-card" data-reveal><a href="#contact" class="country-arrow"><i class="fa-solid fa-arrow-right"></i></a>
                    <div class="country-head"><img src="https://flagcdn.com/w80/ae.png" alt="Flag of the UAE"><h3>Dubai</h3></div>
                    <p>Golden-visa and work permits with fast-track options.</p>
                </article>
                <article class="country-card" data-reveal data-delay="1"><a href="#contact" class="country-arrow"><i class="fa-solid fa-arrow-right"></i></a>
                    <div class="country-head"><img src="https://flagcdn.com/w80/gb-eng.png" alt="Flag of England"><h3>England</h3></div>
                    <p>Skilled-worker and student pathways made simple.</p>
                </article>
                <article class="country-card" data-reveal data-delay="2"><a href="#contact" class="country-arrow"><i class="fa-solid fa-arrow-right"></i></a>
                    <div class="country-head"><img src="https://flagcdn.com/w80/pt.png" alt="Flag of Portugal"><h3>Portugal</h3></div>
                    <p>Digital-nomad and golden-visa programs open year-round.</p>
                </article>
                <article class="country-card" data-reveal data-delay="1"><a href="#contact" class="country-arrow"><i class="fa-solid fa-arrow-right"></i></a>
                    <div class="country-head"><img src="https://flagcdn.com/w80/es.png" alt="Flag of Spain"><h3>Spain</h3></div>
                    <p>Residence by investment with family coverage included.</p>
                </article>
            </div>
        </div>
    </section>

    <!-- ======================= CONTACT / VIDEO ======================= -->
    <section class="contact" id="contact">
        <div class="contact-grid">
            <div class="contact-media" data-reveal="left">
                <img src="{{ asset('images/contact.jpg') }}" alt="Couple traveling with luggage">
                <div class="contact-ribbons">
                    <span>A full-service</span><span>Visa Immigration</span><span>Consul&#8203;ting</span>
                </div>
                <button class="play-btn" aria-label="Play video"><i class="fa-solid fa-play"></i></button>
            </div>
            <div class="contact-form-wrap" data-reveal="right">
                <div class="sec-label chevron-top">Contact With Us</div>
                <form class="contact-form" action="#" method="POST">
                    @csrf
                    <div class="form-row">
                        <input type="text" name="name" placeholder="Your Name" required>
                        <input type="email" name="email" placeholder="Enter Email" required>
                    </div>
                    <div class="form-row">
                        <input type="text" name="subject" placeholder="Subject">
                        <input type="text" name="phone" placeholder="Phone">
                    </div>
                    <textarea name="message" rows="5" placeholder="Message"></textarea>
                    <button type="submit" class="btn btn-black">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <!-- ======================= BRANDS ======================= -->
    <section class="brands">
        <div class="brands-row" data-reveal>
            <span class="brand-logo">Google</span>
            <span class="brand-logo brand-amazon">amazon</span>
            <span class="brand-logo"><i class="fa-brands fa-spotify"></i> Spotify<i class="brand-reg">®</i></span>
        </div>
    </section>

    <!-- ======================= WORKING PROCESS ======================= -->
    <section class="process" id="process">
        <div class="container">
            <div class="sec-label sec-label-center chevron-top-center" data-reveal>Working Process</div>
            <div class="process-track">
                <svg class="process-path" viewBox="0 0 1160 120" preserveAspectRatio="none" aria-hidden="true">
                    <path d="M10 95 C 200 20, 300 20, 420 70 S 700 130, 830 60 S 1050 10, 1150 40" fill="none"/>
                </svg>
                <div class="process-step" data-reveal>
                    <span class="dash-circle process-circle"><i class="fa-solid fa-file-pen"></i><b><i class="fa-solid fa-check"></i></b></span>
                    <span class="step-tag">Step 01</span>
                    <h3>Complete Online Registrations</h3>
                    <p>A short intake form tells our team exactly what your case needs.</p>
                </div>
                <div class="process-step" data-reveal data-delay="1">
                    <span class="dash-circle process-circle"><i class="fa-solid fa-file-invoice-dollar"></i><b><i class="fa-solid fa-check"></i></b></span>
                    <span class="step-tag">Step 02</span>
                    <h3>Documents and Payments</h3>
                    <p>We double-check every attachment and send your file to the embassy.</p>
                </div>
                <div class="process-step" data-reveal data-delay="2">
                    <span class="dash-circle process-circle"><i class="fa-solid fa-address-card"></i><b><i class="fa-solid fa-check"></i></b></span>
                    <span class="step-tag">Step 03</span>
                    <h3>Receive your Visa Now</h3>
                    <p>Collect your approval and start packing — we handle the follow-ups.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= READY TO FLY / STATS ======================= -->
    <section class="fly">
        <div class="container">
            <div class="fly-banner" data-reveal>
                <div class="fly-left">
                    <h2>Ready to fly<br>with us your<br>dream country</h2>
                    <a href="#services" class="link-services">Our Services <i class="fa-solid fa-circle-arrow-right"></i></a>
                </div>
                <div class="fly-center">
                    <img src="{{ asset('images/fly.jpg') }}" alt="Happy traveler celebrating her visa approval">
                </div>
                <div class="fly-stats">
                    <div class="fly-stat">
                        <i class="fa-solid fa-plane-arrival"></i>
                        <div><span class="stat-num" data-count="30">0</span><em>+</em> <label>Visa Categories</label></div>
                    </div>
                    <div class="fly-stat">
                        <i class="fa-solid fa-users"></i>
                        <div><span class="stat-num" data-count="68">0</span><em>k</em> <label>Visa Process</label></div>
                    </div>
                    <div class="fly-stat">
                        <i class="fa-solid fa-earth-americas"></i>
                        <div><span class="stat-num" data-count="99">0</span><em>+</em> <label>Success Rate</label></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= TESTIMONIALS ======================= -->
    <section class="testimonials">
        <div class="container testimonials-grid">
            <div class="testi-side" data-reveal="left">
                <div class="sec-label sec-label-light chevron-top">Testimonials</div>
                <p>We let our results and shared best practices across our worldwide operations speak for each happy traveler we serve.</p>
                <div class="testi-nav">
                    <button class="nav-circle" id="testiPrev" aria-label="Previous testimonial"><i class="fa-solid fa-arrow-left"></i></button>
                    <button class="nav-circle" id="testiNext" aria-label="Next testimonial"><i class="fa-solid fa-arrow-right"></i></button>
                </div>
            </div>
            <div class="testi-viewport" data-reveal="right">
                <div class="testi-track" id="testiTrack">
                    <article class="testi-card">
                        <img class="testi-avatar" src="{{ asset('images/avatar-2.jpg') }}" alt="">
                        <h4>Great Service</h4>
                        <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                        <p>From the first call to the visa decision in hand, everything was explained in plain language and moved fast.</p>
                        <div>
                            <h4 class="testi-name">Tina Brown</h4>
                            <span>Co Founder</span>
                        </div>
                    </article>
                    <article class="testi-card">
                        <img class="testi-avatar" src="{{ asset('images/avatar-1.jpg') }}" alt="">
                        <h4>Great Service</h4>
                        <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i></div>
                        <p>Two previous agents sent my file back with errors. Visago rebuilt it and got approval on the first attempt.</p>
                        <div>
                            <h4 class="testi-name">Mark Wood</h4>
                            <span>Customers</span>
                        </div>
                    </article>
                    <article class="testi-card">
                        <img class="testi-avatar" src="{{ asset('images/avatar-1.jpg') }}" alt="">
                        <h4>Straightforward</h4>
                        <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                        <p>No empty promises, no hidden fees. A clear checklist, reminders on time and my family visa went through.</p>
                        <div>
                            <h4 class="testi-name">Joe Gray</h4>
                            <span>Customers</span>
                        </div>
                    </article>
                    <article class="testi-card">
                        <img class="testi-avatar" src="{{ asset('images/avatar-2.jpg') }}" alt="">
                        <h4>Highly Recommended</h4>
                        <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i></div>
                        <p>The consultants knew the embassy rules better than anyone I dealt with. My work permit was approved in weeks.</p>
                        <div>
                            <h4 class="testi-name">Rose Kate</h4>
                            <span>Customers</span>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= NEWS & BLOG ======================= -->
    <section class="news" id="news">
        <div class="container">
            <div class="sec-label sec-label-center chevron-top-center" data-reveal>News &amp; Blog</div>
            <div class="news-grid">
                <article class="news-card" data-reveal>
                    <a href="#" class="news-thumb"><img src="{{ asset('images/blog-1.jpg') }}" alt="Travel documents spread with a camera and map"><span class="news-date"><b>12</b><i>Feb, 2024</i></span></a>
                    <div class="news-body">
                        <div class="news-meta">
                            <span><i class="fa-solid fa-user"></i> By <a href="#">Ismail</a></span>
                            <span><i class="fa-solid fa-comments"></i> Comments (<a href="#">0</a>)</span>
                        </div>
                        <h3><a href="#">Citizenship Plans: How to Become a UK Resident</a></h3>
                        <a href="#" class="link-services news-more">Read More <i class="fa-solid fa-circle-arrow-right"></i></a>
                    </div>
                </article>
                <article class="news-card" data-reveal data-delay="1">
                    <a href="#" class="news-thumb"><img src="{{ asset('images/blog-2.jpg') }}" alt="Consultos reviewing an application on a laptop"><span class="news-date"><b>12</b><i>Feb, 2024</i></span></a>
                    <div class="news-body">
                        <div class="news-meta">
                            <span><i class="fa-solid fa-user"></i> By <a href="#">Ismail</a></span>
                            <span><i class="fa-solid fa-comments"></i> Comments (<a href="#">0</a>)</span>
                        </div>
                        <h3><a href="#">How Consultations Shape Stronger Visa Files</a></h3>
                        <a href="#" class="link-services news-more">Read More <i class="fa-solid fa-circle-arrow-right"></i></a>
                    </div>
                </article>
                <article class="news-card" data-reveal data-delay="2">
                    <a href="#" class="news-thumb"><img src="{{ asset('images/blog-3.jpg') }}" alt="Globe with travel destinations"><span class="news-date"><b>12</b><i>Feb, 2024</i></span></a>
                    <div class="news-body">
                        <div class="news-meta">
                            <span><i class="fa-solid fa-user"></i> By <a href="#">Ismail</a></span>
                            <span><i class="fa-solid fa-comments"></i> Comments (<a href="#">0</a>)</span>
                        </div>
                        <h3><a href="#">Which Country Fits Your Goals Best in 2024</a></h3>
                        <a href="#" class="link-services news-more">Read More <i class="fa-solid fa-circle-arrow-right"></i></a>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- ======================= CTA STRIP + FOOTER ======================= -->
    <footer class="footer">
        <div class="container">
            <div class="footer-cta" data-reveal>
                <div class="footer-cta-item">
                    <span class="cta-icon"><i class="fa-solid fa-location-dot"></i></span>
                    <div><h4>Call Us Anytime</h4><span>+012 345 678 910</span></div>
                </div>
                <div class="footer-cta-item">
                    <span class="cta-icon"><i class="fa-regular fa-envelope"></i></span>
                    <div><h4>Send Mail</h4><span>info&#64;support.com</span></div>
                </div>
                <div class="footer-cta-item">
                    <span class="cta-icon"><i class="fa-solid fa-phone"></i></span>
                    <div><h4>Our Address</h4><span>13005 Greenville</span></div>
                </div>
            </div>

            <div class="footer-grid">
                <div class="footer-col footer-about">
                    <a href="#" class="logo logo-footer"><i class="fa-solid fa-plane-plane-dotted" style="display:none"></i>VISAGO</a>
                    <p>We work with a passion for turning visas into journeys, helping people move towards their dreams abroad.</p>
                    <a href="#about" class="btn btn-red btn-sm">Read More</a>
                </div>
                <div class="footer-col">
                    <h4>Services</h4>
                    <ul class="footer-links">
                        <li><a href="#services"><i class="fa-solid fa-angles-right"></i>Canada Visa</a></li>
                        <li><a href="#services"><i class="fa-solid fa-angles-right"></i>Japan Visa</a></li>
                        <li><a href="#services"><i class="fa-solid fa-angles-right"></i>Spain Visa</a></li>
                        <li><a href="#services"><i class="fa-solid fa-angles-right"></i>Germany Visa</a></li>
                        <li><a href="#services"><i class="fa-solid fa-angles-right"></i>Italy Visa</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Useful Links</h4>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fa-solid fa-angles-right"></i>Home</a></li>
                        <li><a href="#about"><i class="fa-solid fa-angles-right"></i>About Us</a></li>
                        <li><a href="#services"><i class="fa-solid fa-angles-right"></i>Visa</a></li>
                        <li><a href="#news"><i class="fa-solid fa-angles-right"></i>Blog</a></li>
                        <li><a href="#contact"><i class="fa-solid fa-angles-right"></i>Contact Us</a></li>
                    </ul>
                </div>
                <div class="footer-col footer-news">
                    <h4>Newsletter</h4>
                    <p>Sign up for alerts on our latest blogs, thoughts, and insights.</p>
                    <form class="footer-form" action="#" method="POST">
                        @csrf
                        <input type="email" name="newsletter" placeholder="Your Email address" required>
                        <button type="submit" class="btn btn-red" aria-label="Subscribe"><i class="fa-solid fa-arrow-right"></i></button>
                    </form>
                </div>
            </div>

            <div class="footer-bottom">
                <p>© Copyright {{ date('Y') }} Visago. All rights reserved.</p>
                <div class="footer-social">
                    <a href="#" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>
