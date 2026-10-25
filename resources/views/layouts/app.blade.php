<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta-description', 'Visago — professional visa and immigration consultation for families, students, professionals and businesses.')">
    <title>@yield('title', 'Visago — Visa & Immigration Consultation')</title>

    <link rel="icon" type="image/png" href="{{ asset('images/visago/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Roboto:wght@400;500;700;800;900&family=Caveat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/visago.css') }}">
    @stack('styles')

    <script>
        document.documentElement.classList.add('js');
    </script>
</head>
<body class="@yield('body-class')">
    @include('partials.header')

    <main id="main">
        @yield('content')
    </main>

    @include('partials.footer')

    <button class="back-top" id="backTop" aria-label="Back to top">
        <i class="fa-solid fa-arrow-up"></i>
    </button>

    <div class="video-modal" id="videoModal" aria-hidden="true">
        <div class="video-modal-backdrop" data-video-close></div>
        <div class="video-modal-dialog" role="dialog" aria-modal="true" aria-label="Visago showreel">
            <button class="video-modal-close" data-video-close aria-label="Close video"><i class="fa-solid fa-xmark"></i></button>
            <div class="video-modal-frame" id="videoFrame"></div>
        </div>
    </div>

    <script src="{{ asset('js/visago.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
