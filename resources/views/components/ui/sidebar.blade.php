{{-- Sidebar (collapsible panel) --}}
@props([
    'title' => '',
    'collapsible' => true,
])

@php
    $isRtl = app()->getLocale() === 'ar';
@endphp

<div x-data="{ collapsed: false, isRtl: {{ $isRtl ? 'true' : 'false' }} }"
     {{ $attributes->merge(['class' => 'fixed top-0 z-30 flex flex-col h-screen bg-white border-gray-200 transition-all duration-300 ease-in-out ']) }}
     :class="[collapsed ? 'w-14' : 'w-64', isRtl ? 'right-0' : 'left-0']">

    {{-- Navigation (top bar moved into sidebar) --}}
    <div x-show="!collapsed" class="border-b border-gray-200">
        {{ $navigation ?? '' }}
    </div>

    {{-- Collapsed navigation --}}
    <div x-show="collapsed" class="py-2 border-b border-gray-200">
        {{ $navigationIcons ?? '' }}
    </div>

    {{-- Header with title and collapse toggle --}}
    <div class="flex items-center justify-between px-4 py-4">
        <h2 x-show="!collapsed" class="text-sm font-bold tracking-wider text-gray-800 uppercase truncate whitespace-nowrap">
            <span>{{ $title }}</span>
        </h2>
        @if($collapsible)
        <button @click="collapsed = !collapsed; $dispatch('sidebar-collapsed', { collapsed: collapsed })"
                class="flex-shrink-0 p-1 text-gray-400 transition-colors rounded-lg hover:text-gray-600 hover:bg-gray-100">
            {{-- In LTR: expanded shows left-arrow, collapsed shows right-arrow --}}
            {{-- In RTL: expanded shows right-arrow, collapsed shows left-arrow --}}
            <svg x-show="!collapsed" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!isRtl" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                <path x-show="isRtl" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
            </svg>
            <svg x-show="collapsed" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!isRtl" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                <path x-show="isRtl" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
        </button>
        @endif
    </div>

    {{-- Menu items --}}
    <div class="flex-1 py-2 overflow-y-auto" x-show="!collapsed">
        {{ $slot }}
    </div>

    {{-- Collapsed state: show only icons --}}
    <div class="flex-1 py-2 overflow-y-auto" x-show="collapsed">
        {{ $icons ?? '' }}
    </div>

    {{-- Bottom CTA/promo area --}}
    <div x-show="!collapsed" class="px-3 py-4 border-t border-gray-100">
        {{ $footer ?? '' }}
    </div>
</div>
