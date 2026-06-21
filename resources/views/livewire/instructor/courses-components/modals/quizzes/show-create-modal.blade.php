@if($showQuizCreateModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-on-surface/60" wire:click="closeQuizModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden align-bottom transition-all transform ltr:text-left rtl:text-right bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="mb-4 text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Create Quiz for Section') }}</h3>
                    <form>
                        <div class="mb-4">
                            <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Quiz Title') }}</label>
                            <input type="text" wire:model.lazy="quizCreateTitle"
                                class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary">
                            @error('quizCreateTitle') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Pass Percentage') }}</label>
                            <input type="number" wire:model.lazy="quizCreatePassPercentage" min="1" max="100"
                                class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0">
                            @error('quizCreatePassPercentage') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model.lazy="quizCreateCanReattempt"
                                    class="w-4 h-4 neo-border-sm neo-radius text-on-surface bg-surface-container-low focus:outline-none focus:ring-0 ltr:mr-2 rtl:ml-2">
                                <span class="text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Allow Re-attempt') }}</span>
                            </label>
                            <p class="mt-1 text-xs text-secondary ltr:ml-6 rtl:mr-6">{{ __('messages.Allow students to retake this quiz multiple times') }}</p>
                        </div>
                        <div class="mb-4" x-data="{ show: $wire.entangle('quizCreateCanReattempt') }">
                            <div x-show="show" x-transition>
                                <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Max Attempts') }}</label>
                                <input type="number" wire:model.lazy="quizCreateMaxAttempts" min="1" max="100"
                                    class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0">
                                @error('quizCreateMaxAttempts') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="storeQuiz" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Create') }}
                    </button>
                    <button wire:click="closeQuizModal" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-xs font-bold transition-colors neo-border-sm neo-radius text-on-surface bg-surface-container hover:bg-on-surface hover:text-white sm:mt-0 sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
