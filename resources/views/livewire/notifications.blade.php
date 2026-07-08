<div>
    <div class="px-[24px] max-w-[1400px] mx-auto space-y-6">
        <div class="flex items-center justify-between h-16">
            <h1 class="text-lg font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Notifications') }}</h1>
            <div class="flex items-center gap-2">
                @can('view users')
                    <button wire:click="openSendModal"
                        class="px-3 py-1.5 neo-border neo-radius bg-primary-container text-on-primary-container text-[10px] font-bold uppercase tracking-widest hover:bg-on-surface hover:text-white transition-colors">
                        <i class="fas fa-paper-plane ltr:mr-1 rtl:ml-1"></i>
                        {{ __('messages.Send Notification') }}
                    </button>
                @endcan
                @if($unreadCount > 0)
                    <button wire:click="markAllAsRead"
                        class="px-3 py-1.5 neo-border-sm neo-radius bg-surface-container text-on-primary-container text-[10px] font-bold uppercase tracking-widest hover:bg-on-surface hover:text-white transition-colors">
                        <i class="fas fa-check-double ltr:mr-1 rtl:ml-1"></i>
                        {{ __('messages.Mark all as read') }}
                    </button>
                @endif
                <button wire:click="deleteAllRead"
                    class="px-3 py-1.5 neo-border-sm neo-radius bg-primary-container text-on-primary-container text-[10px] font-bold uppercase tracking-widest hover:bg-on-surface hover:text-white transition-colors">
                    <i class="fas fa-trash ltr:mr-1 rtl:ml-1"></i>
                    {{ __('messages.Delete read') }}
                </button>
            </div>
        </div>

        <div class="flex gap-2 pb-2 border-b-2 border-on-surface">
            <button wire:click="$set('filter', 'all')"
                class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest transition-colors neo-radius
                    {{ $filter === 'all' ? 'bg-primary-container text-on-primary-container neo-border-sm' : 'text-secondary hover:text-on-surface' }}">
                {{ __('messages.All') }} ({{ auth()->user()->notifications()->count() }})
            </button>
            <button wire:click="$set('filter', 'unread')"
                class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest transition-colors neo-radius
                    {{ $filter === 'unread' ? 'bg-primary-container text-on-primary-container neo-border-sm' : 'text-secondary hover:text-on-surface' }}">
                {{ __('messages.Unread') }} ({{ $unreadCount }})
            </button>
            <button wire:click="$set('filter', 'read')"
                class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest transition-colors neo-radius
                    {{ $filter === 'read' ? 'bg-primary-container text-on-primary-container neo-border-sm' : 'text-secondary hover:text-on-surface' }}">
                {{ __('messages.Read') }} ({{ auth()->user()->readNotifications()->count() }})
            </button>
        </div>

        @include('livewire.notifications-components.tables.notifications-list')
    </div>

    @include('livewire.notifications-components.modals.send-notification-modal')
</div>
