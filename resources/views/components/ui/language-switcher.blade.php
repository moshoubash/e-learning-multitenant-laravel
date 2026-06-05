{{-- Language switcher (URL based, redirects to /lang/{locale}) --}}
@php
    $current = app()->getLocale();
@endphp

<div class="px-3 py-2">
    <p class="mb-1 px-2 text-[11px] font-semibold uppercase tracking-widest text-gray-400">
        {{ __('messages.Language') ?? 'Language' }}
    </p>
    <div class="flex items-center gap-1 p-1 bg-gray-100 rounded-lg" role="tablist">
        <a href="{{ url('lang/en') }}"
           class="flex-1 text-center text-xs font-medium py-1.5 rounded-md transition-colors {{ $current === 'en' ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
            EN
        </a>
        <a href="{{ url('lang/ar') }}"
           class="flex-1 text-center text-xs font-medium py-1.5 rounded-md transition-colors {{ $current === 'ar' ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
            AR
        </a>
    </div>
</div>
