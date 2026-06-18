<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @include('partials.auth-dynamic-design-styles')
        @livewireStyles
    </head>
    <body class="font-sans antialiased text-on-surface bg-surface-container-low">
        <div class="flex flex-col items-center min-h-screen px-4 pt-6 sm:justify-center sm:pt-0">
            <div class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                <x-application-logo class="w-20 h-20" />
            </div>

            <div class="w-full p-6 mt-6 sm:max-w-md bg-surface-container-lowest neo-border neo-radius">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
