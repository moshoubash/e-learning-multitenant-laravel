<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="neo-border neo-radius bg-surface-container-lowest p-5">
        <p class="text-xs font-bold uppercase tracking-widest text-secondary">{{ __('messages.Students with points') }}</p>
        <p class="text-3xl font-black text-on-surface mt-1">{{ $stats['total'] }}</p>
    </div>
    <div class="neo-border neo-radius bg-surface-container-lowest p-5">
        <p class="text-xs font-bold uppercase tracking-widest text-secondary">{{ __('messages.Average Points') }}</p>
        <p class="text-3xl font-black text-on-surface mt-1">{{ $stats['average'] }}</p>
    </div>
    <div class="neo-border neo-radius bg-surface-container-lowest p-5">
        <p class="text-xs font-bold uppercase tracking-widest text-secondary">{{ __('messages.Highest Points') }}</p>
        <p class="text-3xl font-black text-on-surface mt-1">{{ $stats['highest'] }}</p>
    </div>
</div>
