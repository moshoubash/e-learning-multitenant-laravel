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

        <div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
            @forelse($notifications as $notification)
                <div class="p-4 border-b border-on-surface/10 flex items-start justify-between gap-4
                    {{ is_null($notification->read_at) ? 'bg-primary-container/5' : '' }}">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-bold truncate text-on-surface">{{ $notification->data['title'] ?? '' }}</p>
                            @if(is_null($notification->read_at))
                                <span class="w-2 h-2 neo-radius bg-error shrink-0"></span>
                            @endif
                        </div>
                        <p class="mt-0.5 text-xs text-secondary">{{ $notification->data['message'] ?? '' }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-[10px] text-secondary/60">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                            @if($notification->data['action_url'] ?? null)
                                <a href="{{ $notification->data['action_url'] }}" target="_blank"
                                    class="text-[10px] font-bold uppercase tracking-widest text-on-primary-container hover:text-on-surface transition-colors">
                                    <i class="fas fa-external-link-alt ltr:mr-1 rtl:ml-1"></i>
                                    {{ __('messages.View') }}
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-1 shrink-0">
                        @if(is_null($notification->read_at))
                            <button wire:click="markAsRead('{{ $notification->id }}')"
                                class="flex items-center justify-center w-8 h-8 text-xs transition-colors neo-border-sm neo-radius text-secondary hover:bg-surface-container-low hover:text-on-surface"
                                title="{{ __('messages.Mark as read') }}">
                                <i class="fas fa-check"></i>
                            </button>
                        @endif
                        <button wire:click="deleteNotification('{{ $notification->id }}')"
                            class="flex items-center justify-center w-8 h-8 text-xs transition-colors neo-border-sm neo-radius text-secondary hover:bg-surface-container-low hover:text-error"
                            title="{{ __('messages.Delete') }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <i class="mb-4 text-5xl text-secondary fas fa-bell-slash"></i>
                    <h3 class="text-sm font-bold tracking-widest uppercase text-on-surface">
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

    {{-- Send Notification Modal --}}
    @if($showSendModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-on-surface/60" wire:click="closeSendModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="inline-block overflow-hidden align-bottom transition-all transform ltr:text-left rtl:text-right bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="mb-4 text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Send Notification') }}</h3>
                        <form wire:submit.prevent="send">
                            <div class="mb-4">
                                <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Title') }}</label>
                                <input type="text" wire:model.lazy="sendTitle"
                                    class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary"
                                    placeholder="{{ __('messages.Notification title') }}">
                                @error('sendTitle') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-4">
                                <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Message') }}</label>
                                <textarea wire:model.lazy="sendMessage" rows="4"
                                    class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary"
                                    placeholder="{{ __('messages.Notification message') }}"></textarea>
                                @error('sendMessage') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-4">
                                <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Send to') }}</label>
                                <select wire:model.lazy="sendRecipientType"
                                    class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0">
                                    <option value="all_students">{{ __('messages.All Students') }}</option>
                                    <option value="all_instructors">{{ __('messages.All Instructors') }}</option>
                                    <option value="all_users">{{ __('messages.All Users') }}</option>
                                    <option value="specific">{{ __('messages.Specific Users') }}</option>
                                </select>
                                @error('sendRecipientType') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                            </div>
                            @if($sendRecipientType === 'specific')
                                <div class="mb-4">
                                    <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Select Users') }}</label>
                                    <input type="text" wire:model.live.debounce.300ms="userSearch"
                                        class="w-full px-3 py-2 mb-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary"
                                        placeholder="{{ __('messages.Search users...') }}">
                                    <div class="overflow-y-auto max-h-48 neo-border-sm neo-radius bg-surface-container-low">
                                        @forelse($users as $user)
                                            <label class="flex items-center gap-3 px-3 py-2 cursor-pointer hover:bg-surface-container-high transition-colors">
                                                <input type="checkbox" wire:model.live="sendSpecificUsers" value="{{ $user->id }}"
                                                    class="w-4 h-4 text-primary-container focus:ring-0 neo-border-sm"
                                                    style="border: 2px solid var(--color-on-surface, #0A0A0A);">
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-bold text-on-surface truncate">{{ $user->name }}</p>
                                                    <p class="text-xs text-secondary truncate">{{ $user->email }}</p>
                                                </div>
                                            </label>
                                        @empty
                                            <p class="p-3 text-xs text-center text-secondary">{{ $userSearch ? __('messages.No users match your search.') : __('messages.Loading users...') }}</p>
                                        @endforelse
                                    </div>
                                    @error('sendSpecificUsers') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                                </div>
                            @endif
                        </form>
                    </div>
                    <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="send" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                            <i class="fas fa-paper-plane ltr:mr-2 rtl:ml-2"></i>
                            {{ __('messages.Send') }} 
                        </button>
                        <button wire:click="closeSendModal" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-xs font-bold transition-colors neo-border-sm neo-radius text-on-surface bg-surface-container hover:bg-on-surface hover:text-white sm:mt-0 sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                            {{ __('messages.Cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
