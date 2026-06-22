<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <x-ui.cdn-assets />

    @stack('styles')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.dynamic-design-styles')
    @livewireStyles
    <style>
        body { background-color: var(--color-surface-container-low, #f4f4f4); background-image: linear-gradient(color-mix(in srgb, var(--color-on-surface, #1a1c1c) 6%, transparent) 1px, transparent 1px), linear-gradient(90deg, color-mix(in srgb, var(--color-on-surface, #1a1c1c) 6%, transparent) 1px, transparent 1px), radial-gradient(circle at 1px 1px, color-mix(in srgb, var(--color-on-surface, #1a1c1c) 15%, transparent) 1px, transparent 1px); background-size: 40px 40px, 40px 40px, 40px 40px; }
    </style>
</head>
<body class="flex h-screen overflow-hidden font-sans antialiased selection:bg-primary-container selection:text-on-primary-container">

    <x-admin.sidebar />

    <main class="flex-1 h-screen overflow-y-auto no-scrollbar pb-20 lg:pb-0 ltr:ml-0 lg:ltr:ml-[240px] rtl:mr-0 lg:rtl:mr-[240px]">
        {{ $slot }}
    </main>

    @php
        $adminNavItems = [
            ['route' => route('tenant.dashboard'), 'active' => 'tenant.dashboard', 'icon' => 'fas fa-home', 'label' => __('messages.Dashboard')],
            ['route' => route('tenant.notifications'), 'active' => 'tenant.notifications*', 'icon' => 'fas fa-bell', 'label' => __('messages.Notifications')],
            ['route' => route('tenant.admin.users'), 'active' => 'tenant.admin.users*', 'icon' => 'fas fa-users', 'label' => __('messages.Users')],
            ['route' => route('tenant.admin.courses'), 'active' => 'tenant.admin.courses*', 'icon' => 'fas fa-book-open', 'label' => __('messages.Courses')],
            ['route' => route('tenant.profile'), 'active' => 'tenant.profile', 'icon' => 'fas fa-user', 'label' => __('messages.Profile')],
        ];
    @endphp
    <x-shared.bottom-nav :items="$adminNavItems" />

    <x-toaster-hub />
    @livewireScripts
    @stack('scripts')
</body>
</html>