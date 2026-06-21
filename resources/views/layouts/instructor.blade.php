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
</head>
<body class="flex h-screen overflow-hidden font-sans antialiased bg-surface-container-low selection:bg-primary-container selection:text-on-primary-container">

    <x-instructor.sidebar />

    <main class="flex-1 h-screen overflow-y-auto no-scrollbar pb-20 lg:pb-0 ltr:ml-0 lg:ltr:ml-[240px] rtl:mr-0 lg:rtl:mr-[240px]">
        {{ $slot }}
    </main>

    @php
        $instructorNavItems = [
            ['route' => route('tenant.dashboard'), 'active' => 'tenant.dashboard', 'icon' => 'fas fa-home', 'label' => __('messages.Dashboard')],
            ['route' => route('tenant.notifications'), 'active' => 'tenant.notifications*', 'icon' => 'fas fa-bell', 'label' => __('messages.Notifications')],
            ['route' => route('tenant.instructor.courses'), 'active' => 'tenant.instructor.courses*', 'icon' => 'fas fa-book-open', 'label' => __('messages.Courses')],
            ['route' => route('tenant.instructor.assignments'), 'active' => 'tenant.instructor.assignments*', 'icon' => 'fas fa-file-alt', 'label' => __('messages.Assignments')],
            ['route' => route('tenant.profile'), 'active' => 'tenant.profile', 'icon' => 'fas fa-user', 'label' => __('messages.Profile')],
        ];
    @endphp
    <x-shared.bottom-nav :items="$instructorNavItems" />

    <x-toaster-hub />
    @livewireScripts
    @stack('scripts')
</body>
</html>