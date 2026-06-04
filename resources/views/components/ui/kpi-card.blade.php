{{-- KPI Card with optional circular progress ring and icon --}}
@props([
    'label' => null,
    'value' => null,
    'progress' => null,
    'color' => '#6366f1',
    'change' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-sm p-5 flex flex-col']) }}>
    <div class="flex items-start justify-between">
        <div class="flex-1">
            @if($label)
                <span class="text-xs font-semibold tracking-wider text-gray-400 uppercase">{{ $label }}</span>
            @endif
            <div class="mt-1 flex items-center gap-3">
                <span class="text-3xl font-bold text-gray-900">{{ $value ?? '—' }}</span>
                @if(isset($progress))
                <div class="relative w-12 h-12">
                    <svg class="-rotate-90 w-12 h-12" viewBox="0 0 36 36">
                        <circle cx="18" cy="18" r="16" fill="none" stroke="#e5e7eb" stroke-width="2.5" />
                        <circle cx="18" cy="18" r="16" fill="none" stroke="{{ $color }}" stroke-width="2.5"
                            stroke-dasharray="100.53"
                            stroke-dashoffset="{{ 100.53 - (100.53 * ($progress / 100)) }}"
                            stroke-linecap="round" />
                    </svg>
                    <span class="absolute inset-0 flex items-center justify-center text-[10px] font-semibold text-gray-700">{{ $progress }}%</span>
                </div>
                @endif
            </div>
            @if(isset($change) && $change !== null)
            <span class="mt-2 text-xs {{ $change >= 0 ? 'text-green-600' : 'text-red-500' }}">
                {{ $change >= 0 ? '+' : '' }}{{ $change }}% {{ __('messages.from last month') }}
            </span>
            @endif
            @if($slot->isNotEmpty())
                <div class="mt-2">{{ $slot }}</div>
            @endif
        </div>
        @isset($icon)
        <div class="flex-shrink-0">
            {{ $icon }}
        </div>
        @endisset
    </div>
</div>
