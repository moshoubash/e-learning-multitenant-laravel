<div class="bg-surface-container-lowest neo-border neo-radius overflow-hidden">
    <div class="p-[24px] border-b-2 border-on-surface flex items-center justify-between">
        <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none">{{ __('messages.Active Users') }}</h3>
        <button wire:click="openCreateModal"
            class="px-4 py-2 neo-border neo-radius bg-primary-container text-on-surface text-xs font-bold uppercase tracking-widest hover:bg-on-surface hover:text-white transition-colors">
            <i class="fas fa-plus ltr:mr-2 rtl:ml-2"></i>
            {{ __('messages.Add User') }}
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full ltr:text-left rtl:text-right">
            <thead class="bg-surface-container-low border-b-2 border-on-surface">
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
                    <tr class="hover:bg-surface-container-low transition-colors duration-150">
                        <td class="p-4 font-bold text-sm text-on-surface">{{ $user->name }}</td>
                        <td class="p-4 text-sm text-secondary">{{ $user->email }}</td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-2.5 py-1 neo-border-sm neo-radius text-[10px] font-bold leading-none
                                @if($user->hasRole('admin')) bg-primary-container text-on-primary-container
                                @elseif($user->hasRole('instructor')) bg-primary-container text-on-primary-container
                                @else bg-surface-container-high text-on-surface @endif">
                                {{ __('messages.'.ucfirst($user->getRoleNames()->first() ?? 'student')) }}
                            </span>
                        </td>
                        <td class="p-4 text-sm text-secondary">
                            {{ app()->getLocale() === 'ar' ? $user->created_at->isoFormat('dddd, D MMMM YYYY') : $user->created_at->translatedFormat('Y-m-d') }}
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <button wire:click="openEditModal({{ $user->id }})"
                                    class="w-8 h-8 neo-border-sm neo-radius flex items-center justify-center text-on-surface hover:bg-primary-container hover:text-on-primary-container transition-colors" title="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <button wire:click="openDeleteModal({{ $user->id }})"
                                    class="w-8 h-8 neo-border-sm neo-radius flex items-center justify-center text-error hover:bg-error hover:text-white transition-colors" title="Delete">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                @if(count($users) == 0)
                    <tr><td colspan="5" class="p-8 text-center text-sm text-secondary">{{ __('messages.No users found.') }}</td></tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t-2 border-on-surface">
        {{ $users->links() }}
    </div>
</div>
