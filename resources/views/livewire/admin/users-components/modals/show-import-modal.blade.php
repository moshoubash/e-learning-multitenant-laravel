@if($showImportModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-on-surface/60" wire:click="closeModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block w-full overflow-hidden align-bottom transition-all transform ltr:text-left rtl:text-right bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="mb-4 text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Import Users') }}</h3>

                    <div class="mb-4 p-3 neo-border-sm neo-radius bg-surface-container-low">
                        <p class="text-xs font-bold text-secondary">
                            {{ __('messages.Current usage') }}: <span class="text-on-surface">{{ $currentUsers }}/{{ $maxUsers }}</span>
                            &middot;
                            {{ __('messages.Remaining') }}: <span class="text-on-surface">{{ $remainingCapacity }}</span>
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Upload Excel File') }}</label>
                        <input type="file" wire:model.lazy="importFile"
                            accept=".xlsx,.xls,.csv"
                            class="w-full text-sm text-on-surface file:mr-4 file:py-2 file:px-4 file:rounded file:border-2 file:border-on-surface file:text-xs file:font-bold file:bg-primary-container file:text-on-primary-container hover:file:bg-on-surface hover:file:text-white">
                        @error('importFile') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                        <p class="mt-1 text-[10px] text-secondary">
                            {{ __('messages.Upload an Excel file (.xlsx, .xls, .csv) with columns: name, email, role.') }}
                            <a wire:click="downloadImportTemplate" class="underline cursor-pointer text-on-surface hover:text-secondary">{{ __('messages.Download template') }}</a>
                        </p>
                    </div>

                    @if(!empty($importResults))
                        <div class="mb-4 p-3 neo-border-sm neo-radius {{ $importResults['imported'] > 0 ? 'bg-primary-container' : 'bg-surface-container-low' }}">
                            <p class="text-xs font-bold text-on-surface">
                                {{ __('messages.Import Results') }}:
                            </p>
                            <p class="text-xs text-secondary mt-1">
                                {{ __('messages.:count users imported', ['count' => $importResults['imported']]) }},
                                {{ __('messages.:count skipped', ['count' => $importResults['skipped']]) }}
                            </p>
                            @if(!empty($importResults['passwords']))
                                <div class="mt-2">
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface mb-1">{{ __('messages.Generated Passwords') }}:</p>
                                    <div class="max-h-32 overflow-y-auto space-y-0.5">
                                        @foreach($importResults['passwords'] as $email => $password)
                                            <p class="text-[10px] text-on-surface font-mono">{{ $email }}: <span class="font-bold">{{ $password }}</span></p>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if(!empty($importResults['errors']))
                                <div class="mt-2 max-h-32 overflow-y-auto space-y-1">
                                    @foreach(array_slice($importResults['errors'], 0, 10) as $error)
                                        <p class="text-[10px] text-error">• {{ $error }}</p>
                                    @endforeach
                                    @if(count($importResults['errors']) > 10)
                                        <p class="text-[10px] text-secondary">... {{ __('messages.and :count more', ['count' => count($importResults['errors']) - 10]) }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="import" type="button" wire:loading.attr="disabled"
                        class="inline-flex justify-center w-full px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        <span wire:loading.remove>{{ __('messages.Import') }}</span>
                        <span wire:loading>{{ __('messages.Importing...') }}</span>
                    </button>
                    <button wire:click="closeModal" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-xs font-bold transition-colors neo-border-sm neo-radius text-on-surface bg-surface-container hover:bg-on-surface hover:text-white sm:mt-0 sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
