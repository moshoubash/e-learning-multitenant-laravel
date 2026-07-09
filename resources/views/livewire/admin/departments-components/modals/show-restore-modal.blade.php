@if($showRestoreModal && $restoringDepartment)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-on-surface/60" wire:click="closeModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block w-full overflow-hidden align-bottom transition-all transform ltr:text-left rtl:text-right bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-sm sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex items-center justify-center w-12 h-12 neo-border neo-radius bg-primary-container">
                            <i class="text-xl text-on-primary-container fas fa-undo"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Restore Department') }}</h3>
                        </div>
                    </div>
                    <p class="text-sm text-on-surface">
                        {{ __('messages.Are you sure you want to restore') }} <strong>{{ $restoringDepartment->name }}</strong>?
                    </p>
                </div>
                <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="restore" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Restore') }}
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
