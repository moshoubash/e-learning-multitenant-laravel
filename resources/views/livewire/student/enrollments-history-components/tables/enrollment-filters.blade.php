<div class="p-[24px] border-b-2 border-on-surface bg-surface-container-low">
    <div class="flex flex-wrap items-center gap-2">
        <button wire:click="$set('statusFilter', '')"
            class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest transition-transform neo-border neo-radius hover:bg-surface-container-high active:scale-95 {{ !$statusFilter ? 'bg-primary-container text-on-primary-container' : 'bg-on-surface-container text-secondary' }}">
            {{ __('messages.All') }}
        </button>
        <button wire:click="$set('statusFilter', 'active')"
            class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest transition-transform neo-border neo-radius hover:bg-surface-container-high active:scale-95 {{ $statusFilter === 'active' ? 'bg-primary-container text-on-primary-container' : 'bg-on-surface-container text-secondary' }}">
            {{ __('messages.Active') }}
        </button>
        <button wire:click="$set('statusFilter', 'completed')"
            class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest transition-transform neo-border neo-radius hover:bg-surface-container-high active:scale-95 {{ $statusFilter === 'completed' ? 'bg-primary-container text-on-primary-container' : 'bg-on-surface-container text-secondary' }}">
            {{ __('messages.Completed') }}
        </button>
        <button wire:click="$set('statusFilter', 'cancelled')"
            class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest transition-transform neo-border neo-radius hover:bg-surface-container-high active:scale-95 {{ $statusFilter === 'cancelled' ? 'bg-error/10 text-error' : 'bg-on-surface-container text-secondary' }}">
            {{ __('messages.Cancelled') }}
        </button>
    </div>
</div>
