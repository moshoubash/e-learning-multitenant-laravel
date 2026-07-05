<div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
    <div class="p-[24px] border-b-2 border-on-surface flex items-center justify-between">
        <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none">{{ __('messages.All Departments') }}</h3>
        @can('create departments')
            <button wire:click="openCreateModal"
                class="px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
                <i class="fas fa-plus ltr:mr-2 rtl:ml-2"></i>
                {{ __('messages.Add Department') }}
            </button>
        @endcan
    </div>
    <div class="overflow-x-auto">
        <table class="w-full ltr:text-left rtl:text-right">
            <thead class="border-b-2 bg-surface-container-low border-on-surface">
                <tr>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Name') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Description') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Users') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Courses') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Created At') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E5E5E5]">
                @forelse ($departments as $department)
                    <tr class="transition-colors duration-150 hover:bg-surface-container-low">
                        <td class="p-4">
                            <div class="text-sm font-bold text-on-surface">{{ $department->name }}</div>
                        </td>
                        <td class="p-4 text-sm text-secondary" dir="auto">{{ Str::limit($department->description, 60) ?: '—' }}</td>
                        <td class="p-4 text-sm text-on-surface">{{ $department->users_count }}</td>
                        <td class="p-4 text-sm text-on-surface">{{ $department->courses_count }}</td>
                        <td class="p-4 text-sm text-secondary">
                            {{ app()->getLocale() === 'ar' ? $department->created_at->isoFormat('dddd, D MMMM YYYY') : $department->created_at->translatedFormat('Y-m-d') }}
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                @can('edit departments')
                                    <button wire:click="openEditModal({{ $department->id }})"
                                        class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Edit">
                                        <i class="text-xs fas fa-edit"></i>
                                    </button>
                                @endcan
                                @can('delete departments')
                                    <button wire:click="openDeleteModal({{ $department->id }})"
                                        class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-error hover:bg-error hover:text-white" title="Delete">
                                        <i class="text-xs fas fa-trash"></i>
                                    </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-sm text-center text-secondary">{{ __('messages.No departments found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t-2 border-on-surface">
        {{ $departments->links() }}
    </div>
</div>
