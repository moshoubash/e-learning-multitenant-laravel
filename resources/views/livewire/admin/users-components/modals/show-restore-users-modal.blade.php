@if($showRestoreModal && $restoringUser)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-on-surface/60 transition-opacity" wire:click="closeModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden ltr:text-left rtl:text-right align-bottom transition-all transform bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto neo-border-sm neo-radius bg-primary-container/30 shrink-0 sm:mx-0">
                            <i class="fas fa-undo text-on-surface"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ltr:ml-4 sm:rtl:mr-4 sm:ltr:text-left rtl:text-right">
                            <h3 class="text-sm font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Restore User') }}</h3>
                            <p class="mt-2 text-sm text-secondary">
                                {{ __('messages.Are you sure you want to restore user :name?', ['name' => $restoringUser->name]) }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="restore" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 neo-border neo-radius bg-primary-container text-on-surface text-xs font-bold uppercase tracking-widest hover:bg-on-surface hover:text-white transition-colors sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Restore') }}
                    </button>
                    <button wire:click="closeModal" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 neo-border-sm neo-radius text-xs font-bold text-on-surface bg-surface-container hover:bg-on-surface hover:text-white transition-colors sm:mt-0 sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
