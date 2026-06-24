<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="description" content="GRID LMS - Learning Management System for Schools, Universities and Organizations">

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
    <style>
        body { background-color: var(--color-surface-container-low, #f4f4f4); background-image: linear-gradient(color-mix(in srgb, var(--color-on-surface, #1a1c1c) 6%, transparent) 1px, transparent 1px), linear-gradient(90deg, color-mix(in srgb, var(--color-on-surface, #1a1c1c) 6%, transparent) 1px, transparent 1px), radial-gradient(circle at 1px 1px, color-mix(in srgb, var(--color-on-surface, #1a1c1c) 15%, transparent) 1px, transparent 1px); background-size: 40px 40px, 40px 40px, 40px 40px; }
    </style>
</head>
<body class="flex h-screen font-sans antialiased selection:bg-primary-container selection:text-on-primary-container">

    <x-student.sidebar />

    {{-- Mobile top bar --}}
    <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between h-14 px-[24px] bg-surface-container-lowest border-b-2 border-on-surface lg:hidden">
        <span class="text-sm font-bold tracking-widest uppercase text-on-surface">{{ config('app.name') }}</span>
        <x-shared.mobile-user-dropdown />
    </header>

    <main class="flex-1  pt-14 lg:pt-0 pb-20 lg:pb-0 ltr:ml-0 lg:ltr:ml-[240px] rtl:mr-0 lg:rtl:mr-[240px]">
        {{ $slot }}
    </main>

    @php
        $studentNavItems = [
            ['route' => route('tenant.dashboard'), 'active' => 'tenant.dashboard', 'icon' => 'fas fa-home', 'label' => __('messages.Dashboard')],
            ['route' => route('tenant.notifications'), 'active' => 'tenant.notifications*', 'icon' => 'fas fa-bell', 'label' => __('messages.Notifications')],
            ['route' => route('tenant.student.courses'), 'active' => 'tenant.student.courses', 'icon' => 'fas fa-graduation-cap', 'label' => __('messages.Browse Courses')],
            ['route' => route('tenant.student.enrolled-courses'), 'active' => 'tenant.student.enrolled-courses*', 'icon' => 'fas fa-play-circle', 'label' => __('messages.My Courses')],
            ['route' => route('tenant.student.enrollments-history'), 'active' => 'tenant.student.enrollments-history*', 'icon' => 'fas fa-history', 'label' => __('messages.History')],
            ['route' => route('tenant.profile'), 'active' => 'tenant.profile', 'icon' => 'fas fa-user', 'label' => __('messages.Profile')],
        ];
    @endphp
    <x-shared.bottom-nav :items="$studentNavItems" />

    <x-toaster-hub />
    @livewireScripts
    @stack('scripts')
</body>
</html>
