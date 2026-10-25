<footer class="site-footer">
    <div class="footer-cta-wrap">
        <div class="container">
            <div class="footer-cta">
                <a href="tel:+012345678910" class="footer-cta-item">
                    <span class="footer-cta-icon"><i class="fa-solid fa-location-dot"></i></span>
                    <span>
                        <strong>Call Us Anytime</strong>
                        <small>+012 345 678 910</small>
                    </span>
                </a>
                <a href="mailto:info@support.com" class="footer-cta-item">
                    <span class="footer-cta-icon"><i class="fa-regular fa-envelope"></i></span>
                    <span>
                        <strong>Send Mail</strong>
                        <small>info@support.com</small>
                    </span>
                </a>
                <a href="{{ route('home') }}#contact" class="footer-cta-item">
                    <span class="footer-cta-icon"><i class="fa-solid fa-phone"></i></span>
                    <span>
                        <strong>Our Address</strong>
                        <small>13005 Greenville</small>
                    </span>
                </a>
            </div>
        </div>
    </div>

    <div class="footer-main">
        <div class="container footer-grid">
            <div class="footer-col footer-about">
                <a href="{{ route('home') }}" class="footer-brand" aria-label="Visago home">
                    <img src="{{ asset('images/visago/logo-red.png') }}" alt="Visago">
                </a>
                <p>We work with a passion of taking challenges and creating new ones in advertising sector.</p>
                <a href="{{ route('about') }}" class="btn btn-red btn-sm">Read More</a>
            </div>

            <div class="footer-col">
                <h4>Services</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}#services"><i class="fa-solid fa-angle-double-right"></i>Canada Visa</a></li>
                    <li><a href="{{ route('home') }}#services"><i class="fa-solid fa-angle-double-right"></i>Japan Visa</a></li>
                    <li><a href="{{ route('home') }}#services"><i class="fa-solid fa-angle-double-right"></i>Spain Visa</a></li>
                    <li><a href="{{ route('home') }}#services"><i class="fa-solid fa-angle-double-right"></i>Germany Visa</a></li>
                    <li><a href="{{ route('home') }}#services"><i class="fa-solid fa-angle-double-right"></i>Italy Visa</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Useful Links</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}"><i class="fa-solid fa-angle-double-right"></i>Home</a></li>
                    <li><a href="{{ route('about') }}"><i class="fa-solid fa-angle-double-right"></i>About Us</a></li>
                    <li><a href="{{ route('home') }}#services"><i class="fa-solid fa-angle-double-right"></i>Visa</a></li>
                    <li><a href="{{ route('home') }}#news"><i class="fa-solid fa-angle-double-right"></i>Blog</a></li>
                    <li><a href="{{ route('home') }}#contact"><i class="fa-solid fa-angle-double-right"></i>Contact Us</a></li>
                </ul>
            </div>

            <div class="footer-col footer-newsletter">
                <h4>Newsletter</h4>
                <p>Sign up for alerts, our latest blogs, thoughts, and insights.</p>
                <form action="#" method="POST" class="newsletter-form">
                    @csrf
                    <label class="sr-only" for="newsletterEmail">Your email address</label>
                    <input id="newsletterEmail" type="email" name="newsletter" placeholder="Your Email address" required>
                    <button type="submit" aria-label="Subscribe"><i class="fa-solid fa-arrow-right"></i></button>
                </form>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container footer-bottom-inner">
            <p>&copy; Copyright 2024 by <a href="https://kodesolution.com" target="_blank" rel="noopener">kodesolution.com</a></p>
            <div class="footer-social">
                <a href="#" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
                <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                <a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
            </div>
        </div>
    </div>
</footer>
