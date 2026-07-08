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
