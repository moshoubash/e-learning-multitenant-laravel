@if($showAiGenerateModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-on-surface/60 transition-opacity" wire:click="closeAiGenerateModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden ltr:text-left rtl:text-right align-bottom transition-all transform bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-on-surface mb-4">
                        <i class="fas fa-robot ltr:mr-2 rtl:ml-2 text-primary"></i>
                        {{ __('messages.Generate Questions with AI') }}
                    </h3>
                    <form>
                        <div class="mb-4">
                            <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Topic') }}</label>
                            <input type="text" wire:model.lazy="aiGenerateTopic"
                                class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0 placeholder:text-secondary"
                                placeholder="{{ __('messages.e.g., PHP Variables, Cloud Computing, etc.') }}">
                            @error('aiGenerateTopic') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Number of Questions') }}</label>
                            <input type="number" wire:model.lazy="aiGenerateCount" min="1" max="10"
                                class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0 placeholder:text-secondary">
                            @error('aiGenerateCount') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block mb-2 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Question Types') }}</label>
                            <div class="flex flex-wrap gap-3">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model.lazy="aiGenerateTypes.single" value="1"
                                        class="w-4 h-4 rounded neo-border-sm bg-surface-container-low text-primary focus:ring-0">
                                    <span class="text-sm text-on-surface">{{ __('messages.Single Choice') }}</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model.lazy="aiGenerateTypes.multiple" value="1"
                                        class="w-4 h-4 rounded neo-border-sm bg-surface-container-low text-primary focus:ring-0">
                                    <span class="text-sm text-on-surface">{{ __('messages.Multiple Choice') }}</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model.lazy="aiGenerateTypes.true_false" value="1"
                                        class="w-4 h-4 rounded neo-border-sm bg-surface-container-low text-primary focus:ring-0">
                                    <span class="text-sm text-on-surface">{{ __('messages.True/False') }}</span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="generateWithAI" wire:loading.attr="disabled" type="button"
                        class="inline-flex justify-center items-center w-full px-4 py-2 neo-border neo-radius bg-primary-container text-on-primary-container text-xs font-bold uppercase tracking-widest hover:bg-on-surface hover:text-white transition-colors sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto disabled:opacity-50">
                        @if($generating)
                            <i class="fas fa-spinner fa-spin ltr:mr-2 rtl:ml-2"></i>
                            {{ __('messages.Generating...') }}
                        @else
                            <i class="fas fa-magic ltr:mr-2 rtl:ml-2"></i>
                            {{ __('messages.Generate') }}
                        @endif
                    </button>
                    <button wire:click="closeAiGenerateModal" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 neo-border-sm neo-radius text-xs font-bold text-on-surface bg-surface-container hover:bg-on-surface hover:text-white transition-colors sm:mt-0 sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
