{{-- Option Create Modal --}}
@if($showOptionCreateModal)
    <div class="fixed inset-0 z-[70] overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-on-surface/60 transition-opacity" wire:click="closeModals"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden ltr:text-left rtl:text-right align-bottom transition-all transform bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-on-surface mb-4">{{ __('messages.Add Option') }}</h3>
                    <form>
                        <div class="mb-4">
                            <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Option Text') }}</label>
                            <input type="text" wire:model.lazy="optionCreateText"
                                class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0 placeholder:text-secondary">
                            @error('optionCreateText') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model.lazy="optionCreateIsCorrect"
                                    class="w-4 h-4 text-primary-container border-on-surface neo-border-sm rounded focus:outline-none">
                                <span class="text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Correct Answer') }}</span>
                            </label>
                        </div>
                    </form>
                </div>
                <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="storeOption" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 neo-border neo-radius bg-primary-container text-on-surface text-xs font-bold uppercase tracking-widest hover:bg-on-surface hover:text-white transition-colors sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Create') }}
                    </button>
                    <button wire:click="closeModals" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 neo-border-sm neo-radius text-xs font-bold text-on-surface bg-surface-container hover:bg-on-surface hover:text-white transition-colors sm:mt-0 sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Option Edit Modal --}}
@if($showOptionEditModal && $editingOption)
    <div class="fixed inset-0 z-[70] overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-on-surface/60 transition-opacity" wire:click="closeModals"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden ltr:text-left rtl:text-right align-bottom transition-all transform bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-on-surface mb-4">{{ __('messages.Edit Option') }}</h3>
                    <form>
                        <div class="mb-4">
                            <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Option Text') }}</label>
                            <input type="text" wire:model.lazy="optionEditText"
                                class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0 placeholder:text-secondary">
                            @error('optionEditText') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model.lazy="optionEditIsCorrect"
                                    class="w-4 h-4 text-primary-container border-on-surface neo-border-sm rounded focus:outline-none">
                                <span class="text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Correct Answer') }}</span>
                            </label>
                        </div>
                    </form>
                </div>
                <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="updateOption" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 neo-border neo-radius bg-primary-container text-on-surface text-xs font-bold uppercase tracking-widest hover:bg-on-surface hover:text-white transition-colors sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Update') }}
                    </button>
                    <button wire:click="closeModals" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 neo-border-sm neo-radius text-xs font-bold text-on-surface bg-surface-container hover:bg-on-surface hover:text-white transition-colors sm:mt-0 sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Option Delete Confirmation --}}
@if($showOptionDeleteModal && $deletingOption)
    <div class="fixed inset-0 z-[70] overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-on-surface/60 transition-opacity" wire:click="closeModals"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden ltr:text-left rtl:text-right align-bottom transition-all transform bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto neo-border-sm neo-radius bg-error/10 shrink-0 sm:mx-0">
                            <i class="text-error fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ltr:ml-4 sm:rtl:mr-4 sm:ltr:text-left rtl:text-right">
                            <h3 class="text-sm font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Delete Option') }}</h3>
                            <p class="mt-2 text-sm text-secondary">
                                {{ __('messages.Are you sure you want to delete this option?') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="deleteOption" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 neo-border neo-radius bg-error text-white text-xs font-bold uppercase tracking-widest hover:bg-on-surface transition-colors sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Delete') }}
                    </button>
                    <button wire:click="closeModals" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 neo-border-sm neo-radius text-xs font-bold text-on-surface bg-surface-container hover:bg-on-surface hover:text-white transition-colors sm:mt-0 sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
