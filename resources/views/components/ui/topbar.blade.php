{{-- Top bar for tenant layouts (mobile menu toggle + page title) --}}
@props([
    'title' => null,
])

<div class="sticky top-0 z-20 flex items-center h-16 px-4 bg-white border-b border-gray-200 lg:hidden">
    <button @click="$dispatch('toggle-sidebar')"
            class="p-2 -ml-2 text-gray-600 transition-colors rounded-lg hover:bg-gray-100 hover:text-gray-900 focus:outline-none"
            aria-label="Open menu">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    @if($title)
        <h1 class="ml-3 text-base font-semibold text-gray-900 truncate">{{ $title }}</h1>
    @endif
</div>
