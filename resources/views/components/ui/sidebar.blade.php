{{-- Sidebar (collapsible on desktop, drawer on mobile) --}}
@props([
    'title' => '',
    'collapsible' => true,
])

@php
    $isRtl = app()->getLocale() === 'ar';
@endphp

<div
    x-data="{
        collapsed: false,
        open: false,
        popupOpen: false,
        isMobile: false,
        isRtl: {{ $isRtl ? 'true' : 'false' }},
        init() {
            this.isMobile = window.innerWidth < 1024;
            this.isRtl = document.documentElement.dir === 'rtl';
        },
        handleResize() {
            const wasMobile = this.isMobile;
            this.isMobile = window.innerWidth < 1024;
            this.isRtl = document.documentElement.dir === 'rtl';
            if (wasMobile && !this.isMobile) {
                this.open = false;
            }
        }
    }"
    @resize.window="handleResize()"
    @keydown.escape.window="if (open && isMobile) open = false"
    @toggle-sidebar.window="open = !open"
    :class="[
        isMobile
            ? (open ? 'translate-x-0' : (isRtl ? 'translate-x-full' : '-translate-x-full'))
            : 'translate-x-0',
        isMobile
            ? 'w-72'
            : (collapsed ? 'w-16' : 'w-64'),
        isRtl ? 'border-l border-gray-200' : 'border-r border-gray-200'
    ]"
    {{ $attributes->merge(['class' => 'fixed top-0 z-40 flex flex-col h-screen bg-white transition-all duration-300 ease-in-out']) }}>

    {{-- Mobile backdrop --}}
    <div
        x-show="open && isMobile"
        x-transition.opacity
        @click="open = false"
        class="fixed inset-0 z-30 bg-gray-900/50 lg:hidden"
        style="display: none;">
    </div>

    {{-- Sidebar content --}}
    <div class="relative z-40 flex flex-col flex-1 min-h-0 bg-white">

        {{-- Header --}}
        <div class="flex items-center h-16 border-b border-gray-200 shrink-0"
             :class="(isMobile || !collapsed)
                ? 'justify-between px-4'
                : 'justify-center px-2'">
            <a href="{{ tenant() ? route('tenant.dashboard') : route('dashboard') }}" wire:navigate
               class="flex items-center gap-2 min-w-0"
               x-show="isMobile || !collapsed">
                <x-application-logo class="text-gray-800 fill-current w-7 h-7 shrink-0" />
                <span class="text-sm font-bold tracking-wider text-gray-800 uppercase truncate">{{ $title }}</span>
            </a>

            {{-- Brand mark for collapsed desktop (just logo) --}}
            <a href="{{ tenant() ? route('tenant.dashboard') : route('dashboard') }}" wire:navigate
               class="items-center justify-center"
               x-show="!isMobile && collapsed">
                <x-application-logo class="text-gray-800 fill-current w-7 h-7" />
            </a>

            <div class="flex items-center gap-1"
                 x-show="isMobile || !collapsed"
                 style="display: none;">
                {{-- Mobile close --}}
                <button x-show="isMobile"
                        @click="open = false"
                        class="p-1.5 text-gray-500 transition-colors rounded-lg hover:bg-gray-100 hover:text-gray-700"
                        aria-label="Close menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                {{-- Desktop collapse --}}
                @if($collapsible)
                <button x-show="!isMobile"
                        @click="collapsed = !collapsed; $dispatch('sidebar-collapsed', { collapsed: collapsed })"
                        class="p-1.5 text-gray-500 transition-colors rounded-lg hover:bg-gray-100 hover:text-gray-700"
                        :aria-label="collapsed ? 'Expand sidebar' : 'Collapse sidebar'">
                    {{-- LTR: expanded shows left arrow (collapse), collapsed shows right arrow (expand) --}}
                    {{-- RTL: expanded shows right arrow, collapsed shows left arrow --}}
                    <svg x-show="!collapsed" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!isRtl" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7M18 19l-7-7 7-7"/>
                        <path x-show="isRtl" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M6 5l7 7-7 7"/>
                    </svg>
                    <svg x-show="collapsed" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!isRtl" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M6 5l7 7-7 7"/>
                        <path x-show="isRtl" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7M18 19l-7-7 7-7"/>
                    </svg>
                </button>
                @endif
            </div>
        </div>

        {{-- Main menu (expanded state on desktop, always on mobile) --}}
        <nav class="flex-1 px-3 py-3 overflow-y-auto"
             x-show="isMobile || !collapsed">
            {{ $slot }}
        </nav>

        {{-- Collapsed state: icons only --}}
        <nav class="flex-1 px-2 py-3 overflow-y-auto"
             x-show="!isMobile && collapsed"
             style="display: none;">
            {{ $icons ?? '' }}
        </nav>

        {{-- Footer (always visible on mobile, when not collapsed on desktop) --}}
        <div class="shrink-0"
             x-show="isMobile || !collapsed">
            {{ $footer ?? '' }}
        </div>

        {{-- Collapsed footer: minimal avatar button that opens a popup --}}
        <div class="relative shrink-0"
             x-show="!isMobile && collapsed"
             style="display: none;"
             @click.outside="popupOpen = false">
            @auth
            <button @click="popupOpen = !popupOpen"
                    class="flex items-center justify-center w-full p-3 border-t border-gray-100 hover:bg-gray-50">
                <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 text-xs font-bold text-indigo-700 bg-indigo-100 rounded-full">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </button>
            <div x-show="popupOpen"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 class="absolute bottom-full left-0 right-0 mb-1 mx-1 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden"
                 style="display: none;">
                <div class="px-3 py-2 border-b border-gray-100">
                    <div class="text-sm font-medium text-gray-800 truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</div>
                </div>
                <a href="{{ route('lang.switch', ['lang' => 'en']) }}"
                   class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 hover:bg-gray-50">
                    <i class="w-5 text-xs text-center fas fa-language"></i>
                    <span>EN</span>
                </a>
                <a href="{{ route('lang.switch', ['lang' => 'ar']) }}"
                   class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 hover:bg-gray-50">
                    <i class="w-5 text-xs text-center fas fa-language"></i>
                    <span>AR</span>
                </a>
                <a href="{{ tenant() ? route('tenant.profile') : route('profile') }}" wire:navigate
                   class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 hover:bg-gray-50">
                    <i class="w-5 text-xs text-center fas fa-user"></i>
                    <span>{{ __('messages.Profile') }}</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit"
                            class="flex items-center w-full gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50">
                        <i class="w-5 text-xs text-center fas fa-sign-out-alt"></i>
                        <span>{{ __('messages.Log Out') }}</span>
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </div>
</div>
