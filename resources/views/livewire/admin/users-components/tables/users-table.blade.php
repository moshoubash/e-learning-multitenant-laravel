<div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
    <div class="p-[24px] border-b-2 border-on-surface flex items-center justify-between">
        <div class="flex items-center gap-4">
            <span class="text-[11px] font-bold text-secondary tracking-wider">
                {{ $currentUsers }}/{{ $maxUsers }} {{ __('messages.users') }}
            </span>
            <button wire:click="openImportModal"
                class="px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius text-on-surface hover:bg-on-surface hover:text-white">
                <i class="fas fa-file-upload ltr:mr-2 rtl:ml-2"></i>
                {{ __('messages.Import') }}
            </button>
            <button wire:click="openCreateModal"
                class="px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
                <i class="fas fa-plus ltr:mr-2 rtl:ml-2"></i>
                {{ __('messages.Add User') }}
            </button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full ltr:text-left rtl:text-right">
            <thead class="border-b-2 bg-surface-container-low border-on-surface">
                <tr>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Name') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Email') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Role') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Created At') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E5E5E5]">
                @foreach ($users as $user)
                    <tr class="transition-colors duration-150 hover:bg-surface-container-low">
                        <td class="p-4 text-sm font-bold text-on-surface">{{ $user->name }}</td>
                        <td class="p-4 text-sm text-secondary">{{ $user->email }}</td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-2.5 py-1 neo-border-sm neo-radius text-[10px] font-bold leading-none
                                @if($user->hasRole('admin')) bg-error text-white
                                @elseif($user->hasRole('instructor')) bg-surface-container-high text-on-surface
                                @else  bg-primary-container text-on-primary-container @endif">
                                {{ __('messages.'.ucfirst($user->getRoleNames()->first() ?? 'student')) }}
                            </span>
                        </td>
                        <td class="p-4 text-sm text-secondary">
                            {{ app()->getLocale() === 'ar' ? $user->created_at->isoFormat('dddd, D MMMM YYYY') : $user->created_at->translatedFormat('Y-m-d') }}
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                @can('edit users')
                                    <button wire:click="openEditModal({{ $user->id }})"
                                        class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Edit">
                                        <i class="text-xs fas fa-edit"></i>
                                    </button>
                                @endcan
                                @can('delete users')
                                    <button wire:click="openDeleteModal({{ $user->id }})"
                                        class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-error hover:bg-error hover:text-white" title="Delete">
                                        <i class="text-xs fas fa-trash"></i>
                                    </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
                @if(count($users) == 0)
                    <tr><td colspan="5" class="p-8 text-sm text-center text-secondary">{{ __('messages.No users found.') }}</td></tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t-2 border-on-surface">
        {{ $users->links() }}
    </div>
</div>
