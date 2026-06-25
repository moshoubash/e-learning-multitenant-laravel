<div class="relative" x-data="{ open: $wire.entangle('showDropdown') }" wire:poll.5s="loadNotifications">
    <button wire:click="toggleDropdown" @click.outside="open = false"
        class="relative flex items-center justify-center w-9 h-9 transition-transform neo-border neo-radius hover:bg-surface-container-high active:scale-95">
        <i class="text-xs fas fa-bell text-primary-container"></i>
        @if($unreadCount > 0)
            <span class="absolute inline-flex items-center justify-center bg-error text-white neo-radius -top-1.5 -right-1.5 w-4 h-4 text-[8px] font-bold">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div x-show="open" x-cloak @click.outside="open = false"
        class="absolute z-50 mt-4 shadow-xl ltr:right-0 rtl:left-0 w-80 neo-border neo-radius bg-surface-container-lowest ltr:text-left rtl:text-right">
        <div class="flex items-center justify-between p-3 border-b-2 border-on-surface">
            <h3 class="text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Notifications') }}</h3>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead"
                    class="text-[10px] font-bold uppercase tracking-widest text-secondary hover:text-primary-container transition-colors">
                    {{ __('messages.Mark all as read') }}
                </button>
            @endif
        </div>

        <div class="overflow-y-auto max-h-80 no-scrollbar">
            @forelse($recentNotifications as $notification)
                <div wire:click="markAsRead('{{ $notification['id'] }}')"
                    class="p-3 border-b border-on-surface/10 hover:bg-surface-container-low cursor-pointer transition-colors
                        {{ is_null($notification['read_at']) ? 'bg-primary-container/5' : '' }}">
                    <p class="text-xs font-bold text-on-surface">{{ $notification['data']['title'] ?? '' }}</p>
                    <p class="mt-0.5 text-[11px] text-secondary">{{ $notification['data']['message'] ?? '' }}</p>
                    <p class="mt-1 text-[10px] text-secondary/60">{{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}</p>
                </div>
            @empty
                <div class="p-6 text-center">
                    <i class="mb-2 text-2xl text-secondary fas fa-bell-slash"></i>
                    <p class="text-xs text-secondary">{{ __('messages.No notifications') }}</p>
                </div>
            @endforelse
        </div>

        @if(count($recentNotifications) > 0)
            <a href="{{ route('tenant.notifications') }}"
                class="block p-3 text-center text-[11px] font-bold uppercase tracking-widest text-on-surface bg-surface-container-low hover:bg-surface-container-high transition-colors neo-radius">
                {{ __('messages.View all notifications') }}
            </a>
        @endif
    </div>
</div>
