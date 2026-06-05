<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts and third-party CDN assets (SRI-protected) -->
    <x-ui.cdn-assets />

    @stack('styles')

    <style>
        *:not(.exclude-this) {
            text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50"
      x-data="{
            sidebarCollapsed: false,
            isRtl: {{ app()->getLocale() === 'ar' ? 'true' : 'false' }},
            isLargeScreen: window.innerWidth >= 1024
         }"
      @sidebar-collapsed.window="sidebarCollapsed = $event.detail.collapsed"
      @resize.window="isLargeScreen = window.innerWidth >= 1024">

    {{-- Sidebar (drawer on mobile, fixed on desktop) --}}
    <x-instructor.sidebar />

    {{-- Mobile top bar (only on small screens) --}}
    <x-ui.topbar />

    {{-- Main Content Area --}}
    <div class="transition-all duration-300 ease-in-out min-h-screen"
         :class="isLargeScreen ? (isRtl
                ? (sidebarCollapsed ? 'mr-14' : 'mr-64')
                : (sidebarCollapsed ? 'ml-14' : 'ml-64')) : ''">
        <div class="px-4 py-6 mx-auto sm:px-6 max-w-7xl">
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>

    <x-toaster-hub />
    @livewireScripts
    @stack('scripts')
</body>
</html>
