<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css">

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
      x-data="{ sidebarCollapsed: false, isRtl: {{ app()->getLocale() === 'ar' ? 'true' : 'false' }}, isLargeScreen: window.innerWidth >= 1024 }"
      @sidebar-collapsed.window="sidebarCollapsed = $event.detail.collapsed"
      @resize.window="isLargeScreen = window.innerWidth >= 1024">

    {{-- Top Navigation Bar --}}
    <div class="fixed top-0 left-0 z-40 w-full transition-all duration-300 ease-in-out"
         :style="isLargeScreen ? `${isRtl ? 'right' : 'left'}: ${sidebarCollapsed ? '3.5rem' : '16rem'};">
        <livewire:layout.navigation />
    </div>

    {{-- Sidebar --}}
    <x-student.sidebar />

    {{-- Main Content Area --}}
    <div class="pt-16 transition-all duration-300 ease-in-out"
         :style="isLargeScreen ? `padding-${isRtl ? 'right' : 'left'}: ${sidebarCollapsed ? '3.5rem' : '16rem'}` : ''">
        <div class="px-6 py-6 mx-auto max-w-7xl">
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
