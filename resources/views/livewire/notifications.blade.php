<div>
    <div class="px-[24px] max-w-[1400px] mx-auto space-y-6">
        <div class="h-16 flex items-center justify-between">
            <h1 class="text-lg font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Notifications') }}</h1>
            <div class="flex items-center gap-2">
                @if($unreadCount > 0)
                    <button wire:click="markAllAsRead"
                        class="px-3 py-1.5 neo-border-sm neo-radius bg-surface-container text-on-surface text-[10px] font-bold uppercase tracking-widest hover:bg-on-surface hover:text-white transition-colors">
                        <i class="fas fa-check-double ltr:mr-1 rtl:ml-1"></i>
                        {{ __('messages.Mark all as read') }}
                    </button>
                @endif
                <button wire:click="deleteAllRead"
                    class="px-3 py-1.5 neo-border-sm neo-radius bg-surface-container text-on-surface text-[10px] font-bold uppercase tracking-widest hover:bg-on-surface hover:text-white transition-colors">
                    <i class="fas fa-trash ltr:mr-1 rtl:ml-1"></i>
                    {{ __('messages.Delete read') }}
                </button>
            </div>
        </div>

        <div class="flex gap-2 border-b-2 border-on-surface pb-2">
            <button wire:click="$set('filter', 'all')"
                class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest transition-colors neo-radius
                    {{ $filter === 'all' ? 'bg-primary-container text-on-surface neo-border-sm' : 'text-secondary hover:text-on-surface' }}">
                {{ __('messages.All') }} ({{ auth()->user()->notifications()->count() }})
            </button>
            <button wire:click="$set('filter', 'unread')"
                class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest transition-colors neo-radius
                    {{ $filter === 'unread' ? 'bg-primary-container text-on-surface neo-border-sm' : 'text-secondary hover:text-on-surface' }}">
                {{ __('messages.Unread') }} ({{ $unreadCount }})
            </button>
            <button wire:click="$set('filter', 'read')"
                class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest transition-colors neo-radius
                    {{ $filter === 'read' ? 'bg-primary-container text-on-surface neo-border-sm' : 'text-secondary hover:text-on-surface' }}">
                {{ __('messages.Read') }} ({{ auth()->user()->readNotifications()->count() }})
            </button>
        </div>

        <div class="bg-surface-container-lowest neo-border neo-radius overflow-hidden">
            @forelse($notifications as $notification)
                <div class="p-4 border-b border-on-surface/10 flex items-start justify-between gap-4
                    {{ is_null($notification->read_at) ? 'bg-primary-container/5' : '' }}">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-bold text-on-surface truncate">{{ $notification->data['title'] ?? '' }}</p>
                            @if(is_null($notification->read_at))
                                <span class="w-2 h-2 neo-radius bg-error shrink-0"></span>
                            @endif
                        </div>
                        <p class="mt-0.5 text-xs text-secondary">{{ $notification->data['message'] ?? '' }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-[10px] text-secondary/60">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                            @if($notification->data['action_url'] ?? null)
                                <a href="{{ $notification->data['action_url'] }}" target="_blank"
                                    class="text-[10px] font-bold uppercase tracking-widest text-primary-container hover:text-on-surface transition-colors">
                                    <i class="fas fa-external-link-alt ltr:mr-1 rtl:ml-1"></i>
                                    {{ __('messages.View') }}
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-1 shrink-0">
                        @if(is_null($notification->read_at))
                            <button wire:click="markAsRead('{{ $notification->id }}')"
                                class="w-8 h-8 flex items-center justify-center neo-border-sm neo-radius text-xs text-secondary hover:bg-surface-container-low hover:text-on-surface transition-colors"
                                title="{{ __('messages.Mark as read') }}">
                                <i class="fas fa-check"></i>
                            </button>
                        @endif
                        <button wire:click="deleteNotification('{{ $notification->id }}')"
                            class="w-8 h-8 flex items-center justify-center neo-border-sm neo-radius text-xs text-secondary hover:bg-surface-container-low hover:text-error transition-colors"
                            title="{{ __('messages.Delete') }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <i class="mb-4 text-5xl text-secondary fas fa-bell-slash"></i>
                    <h3 class="text-sm font-bold uppercase tracking-widest text-on-surface">
                        {{ $filter === 'all' ? __('messages.No notifications') : ($filter === 'unread' ? __('messages.No unread notifications') : __('messages.No read notifications')) }}
                    </h3>
                    <p class="mt-2 text-sm text-secondary">{{ __('messages.When you get notifications, they will appear here.') }}</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="py-4">
                {{ $notifications->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>
</div>
