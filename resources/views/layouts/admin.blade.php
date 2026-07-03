<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="description" content="GRID LMS - Learning Management System for Organizations">
    <meta name="google-site-verification" content="QsUWYyMIXN1Y1qUFhHUXJ4nQk6NqM8LTNvAqW16rTx4" />
    <link rel="icon" type="image/icon" href="{{ asset('images/grid_icon_logo.ico') }}">

    <x-ui.cdn-assets />

    @stack('styles')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.dynamic-design-styles')
    @livewireStyles
    <style>
        body {
            background-color: var(--color-surface-container-low, #f4f4f4);
            background-image: linear-gradient(color-mix(in srgb, var(--color-on-surface, #1a1c1c) 0%, transparent) 1px, transparent 1px), linear-gradient(90deg, color-mix(in srgb, var(--color-on-surface, #1a1c1c) 0%, transparent) 1px, transparent 1px), radial-gradient(circle at 1px 1px, color-mix(in srgb, var(--color-on-surface, #1a1c1c) 15%, transparent) 1.5px, transparent 2.5px);
            background-size: 50px 50px, 50px 50px, 50px 50px;
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden font-sans antialiased selection:bg-primary-container selection:text-on-primary-container">

    <x-admin.sidebar />

    {{-- Mobile top bar --}}
    <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between h-14 px-[24px] bg-surface-container-lowest border-b-2 border-on-surface lg:hidden">
        <span class="text-sm font-bold tracking-widest uppercase text-on-surface">{{ config('app.name') }}</span>
        <x-shared.mobile-user-dropdown />
    </header>

    <main class="flex-1 h-screen overflow-y-auto no-scrollbar pt-14 lg:pt-0 pb-20 lg:pb-0 ltr:ml-0 lg:ltr:ml-[240px] rtl:mr-0 lg:rtl:mr-[240px]">
        {{ $slot }}
    </main>

    @php
        $adminNavItems = [
            ['route' => route('tenant.dashboard'), 'active' => 'tenant.dashboard', 'icon' => 'fas fa-home', 'label' => __('messages.Dashboard')],
            ['route' => route('tenant.admin.users'), 'active' => 'tenant.admin.users*', 'icon' => 'fas fa-users', 'label' => __('messages.Users')],
            ['route' => route('tenant.admin.enrollments'), 'active' => 'tenant.admin.enrollments*', 'icon' => 'fas fa-user-graduate', 'label' => __('messages.Enrollments')],
            ['route' => route('tenant.admin.roles-permissions'), 'active' => 'tenant.admin.roles-permissions*', 'icon' => 'fas fa-shield-alt text-on-surface', 'label' => __('messages.Roles')],
            ['route' => route('tenant.profile'), 'active' => 'tenant.profile', 'icon' => 'fas fa-user', 'label' => __('messages.Profile')],
        ];
    @endphp
    <x-shared.bottom-nav :items="$adminNavItems" />

    <x-toaster-hub />
    @livewireScripts
    @stack('scripts')
</body>
</html>
