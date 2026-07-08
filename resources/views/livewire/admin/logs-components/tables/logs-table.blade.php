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
