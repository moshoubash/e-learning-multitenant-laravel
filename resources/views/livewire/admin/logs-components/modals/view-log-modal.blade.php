@if($showViewModal && $viewingFile)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-on-surface/60" wire:click="closeModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block w-full overflow-hidden align-bottom transition-all transform bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold tracking-widest uppercase text-on-surface">{{ $viewingFile }}</h3>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Last 500 lines') }}</span>
                    </div>
                    <div class="p-4 overflow-auto max-h-96 neo-border-sm neo-radius bg-on-surface text-surface-container-lowest" style="background-color: #0A0A0A; color: #E2E2E2;">
                        <pre class="font-mono text-xs leading-relaxed text-left whitespace-pre-wrap">{{ $logContent ?: __('messages.File is empty') }}</pre>
                    </div>
                </div>
                <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="closeModal" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-xs font-bold transition-colors neo-border-sm neo-radius text-on-surface bg-surface-container hover:bg-on-surface hover:text-white sm:mt-0 sm:w-auto">
                        {{ __('messages.Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
