@if($deletedDepartments->isNotEmpty())
    <div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
        <div class="p-[24px] border-b-2 border-on-surface">
            <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none">{{ __('messages.Deleted Departments') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full ltr:text-left rtl:text-right">
                <thead class="border-b-2 bg-surface-container-low border-on-surface">
                    <tr>
                        <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Name') }}</th>
                        <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Deleted At') }}</th>
                        <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E5E5]">
                    @foreach ($deletedDepartments as $department)
                        <tr class="transition-colors duration-150 hover:bg-surface-container-low">
                            <td class="p-4">
                                <span class="text-sm font-bold text-on-surface">{{ $department->name }}</span>
                            </td>
                            <td class="p-4 text-sm text-secondary">
                                {{ $department->deleted_at->translatedFormat('Y-m-d') }}
                            </td>
                            <td class="p-4">
                                <button wire:click="openRestoreModal({{ $department->id }})"
                                    class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Restore">
                                    <i class="text-xs fas fa-undo"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
