<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="google-site-verification" content="QsUWYyMIXN1Y1qUFhHUXJ4nQk6NqM8LTNvAqW16rTx4" />

        <title>Grid LMS</title>
        <meta name="description" content="Grid LMS — Learning management system. Empower educators, track analytics, and build learning communities.">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300..900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
        <link rel="icon" type="image/icon" href="{{ asset('images/grid_icon_logo.ico') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @include('partials.dynamic-design-styles')
        @livewireStyles

        <style>
            body {
                font-family: 'Space Grotesk', sans-serif;
                background-color: #F4F4F4;
                color: #0A0A0A;
            }

            .landing-content > main > section,
            .landing-content > section,
            .landing-content > footer {
                background-image: linear-gradient(color-mix(in srgb, #1a1c1c 0%, transparent) 1px, transparent 1px), linear-gradient(90deg, color-mix(in srgb, #1a1c1c 0%, transparent) 1px, transparent 1px), radial-gradient(circle at 1px 1px, color-mix(in srgb, #1a1c1c 15%, transparent) 1.5px, transparent 2.5px);
                background-size: 50px 50px, 50px 50px, 50px 50px;
            }

            [lang="ar"] .landing-content :not(.material-symbols-outlined) {
                font-family: 'Cairo', sans-serif !important;
            }

            .neo-border {
                border: 2px solid #0A0A0A;
            }

            .card {
                background-color: #FFFFFF;
                border: 2px solid #0A0A0A;
                padding: 24px;
                border-radius: 4px;
            }

            .step-square {
                width: 64px;
                height: 64px;
                background-color: #FFD600;
                border: 2px solid #0A0A0A;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                font-size: 32px;
            }

            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            }

            .step-line {
                position: absolute;
                top: 32px;
                bottom: 32px;
                width: 4px;
                background-color: #0A0A0A;
                display: none;
            }

            @media (min-width: 768px) {
                .step-line {
                    display: block;
                }
            }

            [dir="ltr"] .step-line {
                left: 32px;
            }

            [dir="rtl"] .step-line {
                right: 32px;
            }

            *{
                scroll-behavior: smooth;
            }
        </style>
    </head>
    <body class="overflow-x-hidden">
        {{ $slot }}

        @livewireScripts
        <script>
            document.querySelectorAll('button, .btn').forEach(el => {
                el.addEventListener('mousedown', () => el.style.transform = 'translate(2px, 2px)');
                el.addEventListener('mouseup', () => el.style.transform = 'translate(0px, 0px)');
                el.addEventListener('mouseleave', () => el.style.transform = 'translate(0px, 0px)');
            });

            const steps = document.querySelectorAll('.step-square');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.transform = 'scale(1.1)';
                        setTimeout(() => entry.target.style.transform = 'scale(1)', 200);
                    }
                });
            }, { threshold: 0.5 });
            steps.forEach(step => observer.observe(step));
        </script>
    </body>
</html>
