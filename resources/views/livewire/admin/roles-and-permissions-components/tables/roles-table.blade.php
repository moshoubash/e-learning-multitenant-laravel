<div class="flex items-center justify-between">
    <p class="text-xs font-bold tracking-widest uppercase text-secondary">{{ __('messages.Total roles') }}: {{ $roles->total() }}</p>
    <button wire:click="openRoleCreateModal"
        class="px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
        <i class="fas fa-plus ltr:mr-2 rtl:ml-2"></i>
        {{ __('messages.Create Role') }}
    </button>
</div>

<div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
    <div class="overflow-x-auto">
        <table class="w-full ltr:text-left rtl:text-right">
            <thead class="border-b-2 bg-surface-container-low border-on-surface">
                <tr>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">#</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Name') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Permissions') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Users') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E5E5E5]">
                @forelse ($roles as $role)
                    <tr class="transition-colors duration-150 hover:bg-surface-container-low">
                        <td class="p-4 font-mono text-sm text-on-surface">{{ $role->id }}</td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                @if($role->name === 'admin')
                                    <i class="fas fa-shield-alt text-on-surface"></i>
                                @elseif($role->name === 'instructor')
                                    <i class="fas fa-chalkboard-teacher text-on-surface"></i>
                                @elseif($role->name === 'student')
                                    <i class="fas fa-graduation-cap text-on-surface"></i>
                                @else
                                    <i class="fas fa-user-tag text-on-surface"></i>
                                @endif
                                <span class="text-sm font-bold text-on-surface">{{ ucfirst($role->name) }}</span>
                                @if(in_array($role->name, ['admin', 'instructor', 'student']))
                                    <span class="px-2 py-0.5 neo-border-sm neo-radius text-[9px] font-bold uppercase tracking-wider bg-surface-container text-secondary">{{ __('messages.Core') }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="text-sm text-on-surface">{{ $role->permissions->count() }} {{ __('messages.permissions') }}</span>
                        </td>
                        <td class="p-4">
                            <span class="text-sm text-on-surface">{{ $role->users->count() }}</span>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <button wire:click="openAssignModal({{ $role->id }})"
                                    class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="{{ __('messages.Assign Permissions') }}">
                                    <i class="text-xs fas fa-check-double"></i>
                                </button>
                                @if(! in_array($role->name, ['admin', 'instructor', 'student']))
                                    <button wire:click="openRoleEditModal({{ $role->id }})"
                                        class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="{{ __('messages.Edit') }}">
                                        <i class="text-xs fas fa-edit"></i>
                                    </button>
                                    <button wire:click="openRoleDeleteModal({{ $role->id }})"
                                        class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-error hover:bg-error hover:text-white" title="{{ __('messages.Delete') }}">
                                        <i class="text-xs fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-sm text-center text-secondary">{{ __('messages.No roles found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($roles->hasPages())
        <div class="p-4 border-t-2 border-on-surface">
            {{ $roles->links() }}
        </div>
    @endif
</div>
