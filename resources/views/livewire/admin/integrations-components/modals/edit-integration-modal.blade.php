@if($showEditModal && $editingIntegration)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-on-surface/60" wire:click="closeModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block w-full overflow-hidden align-bottom transition-all transform ltr:text-left rtl:text-right bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="mb-4 text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Edit Integration') }}: {{ ucfirst($editingIntegration->provider) }}</h3>
                    <form>
                        <div class="mb-4">
                            <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Client ID') }}</label>
                            <input type="text" wire:model.lazy="editClientId"
                                class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary">
                            @error('editClientId') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Client Secret') }}</label>
                            <input type="password" wire:model.lazy="editClientSecret"
                                class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary">
                            @error('editClientSecret') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Redirect URL') }}</label>
                            <input type="url" wire:model.lazy="editRedirectUrl"
                                @if($editingIntegration->provider === 'paypal') disabled @endif
                                class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary @if($editingIntegration->provider === 'paypal') opacity-50 cursor-not-allowed @endif">
                            @if($editingIntegration->provider === 'paypal')
                                <p class="mt-1 text-xs text-secondary">
                                    <i class="fas fa-info-circle ltr:mr-1 rtl:ml-1"></i>
                                    {{ __('messages.PayPal does not require a redirect URL.') }}
                                </p>
                            @endif
                            @error('editRedirectUrl') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model.lazy="editIsActive"
                                    class="w-4 h-4 neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 ltr:mr-2 rtl:ml-2">
                                <span class="text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Active') }}</span>
                            </label>
                        </div>
                    </form>
                </div>
                <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="update" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Update') }}
                    </button>
                    <button wire:click="closeModal" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-xs font-bold transition-colors neo-border-sm neo-radius text-on-surface bg-surface-container hover:bg-on-surface hover:text-white sm:mt-0 sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
