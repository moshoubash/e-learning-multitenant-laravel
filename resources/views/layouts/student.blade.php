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
<body class="flex h-screen overflow-hidden font-sans antialiased bg-surface-container-low selection:bg-primary-container selection:text-on-primary-container">

    <x-student.sidebar />

    <main class="flex-1 overflow-y-auto no-scrollbar pb-20 lg:pb-0 ltr:ml-0 lg:ltr:ml-[240px] rtl:mr-0 lg:rtl:mr-[240px]">
        {{ $slot }}
    </main>

    @php
        $studentNavItems = [
            ['route' => route('tenant.dashboard'), 'active' => 'tenant.dashboard', 'icon' => 'fas fa-home', 'label' => __('messages.Dashboard')],
            ['route' => route('tenant.notifications'), 'active' => 'tenant.notifications*', 'icon' => 'fas fa-bell', 'label' => __('messages.Notifications')],
            ['route' => route('tenant.student.courses'), 'active' => 'tenant.student.courses', 'icon' => 'fas fa-graduation-cap', 'label' => __('messages.Browse Courses')],
            ['route' => route('tenant.student.enrolled-courses'), 'active' => 'tenant.student.enrolled-courses*', 'icon' => 'fas fa-play-circle', 'label' => __('messages.My Courses')],
            ['route' => route('tenant.profile'), 'active' => 'tenant.profile', 'icon' => 'fas fa-user', 'label' => __('messages.Profile')],
        ];
    @endphp
    <x-shared.bottom-nav :items="$studentNavItems" />

    <x-toaster-hub />
    @livewireScripts
    @stack('scripts')
</body>
</html>
