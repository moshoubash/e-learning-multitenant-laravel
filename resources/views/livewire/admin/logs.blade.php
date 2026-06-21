<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none tracking-[0.08em]">{{ __('messages.Log Tracking') }}</h2>
        <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Monitor application and security log files') }}</p>
    </div>
    <div class="flex items-center gap-2">
        @livewire('shared.notification-bell')
    </div>
</header>

    <div class="p-[24px] max-w-[1400px] mx-auto space-y-6">
        {{-- Log Files Table --}}
        <div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
            <div class="overflow-x-auto">
                <table class="w-full ltr:text-left rtl:text-right">
                    <thead class="border-b-2 bg-surface-container-low border-on-surface">
                        <tr>
                            <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.File Name') }}</th>
                            <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Size') }}</th>
                            <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Lines') }}</th>
                            <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Last Modified') }}</th>
                            <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E5E5]">
                        @forelse ($logFiles as $file)
                            <tr class="transition-colors duration-150 hover:bg-surface-container-low">
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-file-alt text-on-surface"></i>
                                        <span class="text-sm font-bold text-on-surface">{{ $file['name'] }}</span>
                                    </div>
                                </td>
                                <td class="p-4 text-sm text-on-surface">{{ $file['size_formatted'] }}</td>
                                <td class="p-4 text-sm text-on-surface">{{ number_format($file['lines']) }}</td>
                                <td class="p-4 text-sm text-on-surface">{{ $file['last_modified_formatted'] }}</td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <button wire:click="openViewModal('{{ $file['name'] }}')"
                                            class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="{{ __('messages.View') }}">
                                            <i class="text-xs fas fa-eye"></i>
                                        </button>
                                        <button wire:click="openDeleteModal('{{ $file['name'] }}')"
                                            class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-error hover:bg-error hover:text-white" title="{{ __('messages.Delete') }}">
                                            <i class="text-xs fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-sm text-center text-secondary">{{ __('messages.No log files found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- View Modal --}}
    @if($showViewModal && $viewingFile)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-on-surface/60" wire:click="closeModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="inline-block overflow-hidden align-bottom transition-all transform ltr:text-left rtl:text-right bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-bold tracking-widest uppercase text-on-surface">{{ $viewingFile }}</h3>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Last 500 lines') }}</span>
                        </div>
                        <div class="p-4 overflow-auto max-h-96 neo-border-sm neo-radius bg-on-surface text-surface-container-lowest" style="background-color: #0A0A0A; color: #E2E2E2;">
                            <pre class="font-mono text-xs leading-relaxed whitespace-pre-wrap">{{ $logContent ?: __('messages.File is empty') }}</pre>
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

    {{-- Delete Modal --}}
    @if($showDeleteModal && $deletingFile)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-on-surface/60" wire:click="closeModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="inline-block overflow-hidden align-bottom transition-all transform ltr:text-left rtl:text-right bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="flex items-center justify-center w-12 h-12 mx-auto neo-border-sm neo-radius bg-error/10 shrink-0 sm:mx-0">
                                <i class="text-error fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ltr:ml-4 sm:rtl:mr-4 sm:ltr:text-left sm:rtl:text-right">
                                <h3 class="text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Delete Log File') }}</h3>
                                <p class="mt-2 text-sm text-secondary">
                                    {{ __('messages.Are you sure you want to delete the log file :file?', ['file' => $deletingFile]) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="delete" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 text-xs font-bold tracking-widest text-white uppercase transition-colors neo-border neo-radius bg-error hover:bg-on-surface sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                            {{ __('messages.Delete') }}
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
</div>
