<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('messages.Log in') }} | GRID LMS</title>
        <meta name="description" content="GRID LMS - Learning Management System for Schools, Universities and Organizations">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @include('partials.auth-dynamic-design-styles')
        @livewireStyles
        <style>
            body { background-color: var(--color-surface-container-low, #f4f4f4); background-image: linear-gradient(color-mix(in srgb, var(--color-on-surface, #1a1c1c) 6%, transparent) 1px, transparent 1px), linear-gradient(90deg, color-mix(in srgb, var(--color-on-surface, #1a1c1c) 6%, transparent) 1px, transparent 1px), radial-gradient(circle at 1px 1px, color-mix(in srgb, var(--color-on-surface, #1a1c1c) 15%, transparent) 1px, transparent 1px); background-size: 40px 40px, 40px 40px, 40px 40px; }
        </style>
    </head>
    <body class="flex items-center justify-center min-h-screen p-4 font-sans antialiased">

        <main class="w-full max-w-md">
            {{-- Brand --}}
            <div class="mb-10 text-center">
                <h1 class="text-4xl italic font-bold tracking-tighter uppercase text-on-surface">
                    GRID
                    {{-- <span class="px-2 ltr:ml-1 rtl:mr-1 -tracking-[0.02em] not-italic" style="background-color: var(--color-primary-container, #FFD600); border: 2px solid var(--color-on-surface, #0A0A0A); border-radius: 4px;">LMS</span> --}}
                <sup class="px-2 -tracking-[0.02em] not-italic" style="background-color: var(--color-primary-container, #FFD600); border: 2px solid var(--color-on-surface, #0A0A0A); border-radius: 50px; font-size: 10px; vertical-align: super; position: static;">v1.0.0</sup>
                </h1>
                <p class="mt-4 text-sm font-medium tracking-wide uppercase opacity-70 text-on-surface">
                                        Learning Management System for Schools, Universities and Organizations
                </p>
            </div>

            {{-- Card --}}
            <section class="p-8 border-2 bg-surface-container-lowest border-on-surface rounded-neo">
                {{ $slot }}
            </section>

            {{-- After-card content (account links, etc.) --}}
            @stack('auth-extra')
        </main>

        @livewireScripts
    </body>
</html>
