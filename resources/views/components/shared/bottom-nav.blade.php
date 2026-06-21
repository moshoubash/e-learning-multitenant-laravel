@props(['items' => []])

@php
    $currentLocale = app()->getLocale();
@endphp

<nav class="fixed bottom-0 left-0 right-0 z-50 bg-surface-container-lowest border-t-2 border-on-surface lg:hidden safe-area-bottom">
    <div class="flex items-center justify-around px-1 py-1">
        @foreach($items as $item)
            <a href="{{ $item['route'] }}" wire:navigate
               class="flex flex-col items-center gap-0.5 px-2 py-1.5 neo-radius min-w-0 flex-1 {{ request()->routeIs($item['active']) ? 'bg-primary-container text-on-primary-container font-bold' : 'text-on-surface hover:bg-surface-container-high transition-colors duration-100' }}">
                <i class="{{ $item['icon'] }} text-lg"></i>
                <span class="text-[10px] font-medium leading-tight text-center">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</nav>
