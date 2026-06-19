<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <x-ui.cdn-assets />

    <style>
        :root {
            --primary-theme-color: var(--color-primary-container, #FFD600);
        }
    </style>

    @stack('styles')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.dynamic-design-styles')
    @livewireStyles
</head>
<body class="flex font-sans antialiased bg-surface-container-low selection:bg-primary-container selection:text-on-surface">

    <x-student.sidebar />

    <main class="ltr:ml-[240px] rtl:mr-[240px] flex-1 overflow-y-auto no-scrollbar">
        {{ $slot }}
    </main>

    <x-toaster-hub />
    @livewireScripts
    @stack('scripts')
</body>
</html>
