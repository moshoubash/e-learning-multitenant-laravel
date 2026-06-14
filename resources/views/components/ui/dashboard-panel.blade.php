{{-- Dashboard panel/card with title and table body --}}
@props([
    'title' => null,
    'icon' => null,
    'action' => null, // slot for a header action
    'padding' => 'p-0',
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-sm']) }}>
    @if($title || $action)
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="flex items-center text-sm font-semibold tracking-wider text-gray-700 uppercase">
                @if($icon)
                    <i class="text-gray-400 text-center {{ $icon }} {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                @endif
                {{ $title }}
            </h3>
            @if($action)
                <div class="flex items-center">{{ $action }}</div>
            @endif
        </div>
    @endif

    <div class="{{ $padding }}">
        {{ $slot }}
    </div>
</div>
