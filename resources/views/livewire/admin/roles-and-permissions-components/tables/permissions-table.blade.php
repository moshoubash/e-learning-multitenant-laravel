<div class="flex items-center justify-between">
    <p class="text-xs font-bold tracking-widest uppercase text-secondary">{{ __('messages.Total permissions') }}: {{ $permissions->total() }}</p>
    <button wire:click="openPermissionCreateModal"
        class="px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
        <i class="fas fa-plus ltr:mr-2 rtl:ml-2"></i>
        {{ __('messages.Create Permission') }}
    </button>
</div>

<div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
    <div class="overflow-x-auto">
        <table class="w-full ltr:text-left rtl:text-right">
            <thead class="border-b-2 bg-surface-container-low border-on-surface">
                <tr>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">#</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Name') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Guard') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Roles using it') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E5E5E5]">
                @forelse ($permissions as $permission)
                    <tr class="transition-colors duration-150 hover:bg-surface-container-low">
                        <td class="p-4 font-mono text-sm text-on-surface">{{ $permission->id }}</td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-key text-on-surface"></i>
                                <span class="text-sm font-bold text-on-surface">{{ $permission->name }}</span>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold bg-surface-container text-secondary">{{ $permission->guard_name }}</span>
                        </td>
                        <td class="p-4">
                            <span class="text-sm text-on-surface">{{ $permission->roles->count() }} {{ __('messages.roles') }}</span>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <button wire:click="openPermissionEditModal({{ $permission->id }})"
                                    class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="{{ __('messages.Edit') }}">
                                    <i class="text-xs fas fa-edit"></i>
                                </button>
                                <button wire:click="openPermissionDeleteModal({{ $permission->id }})"
                                    class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-error hover:bg-error hover:text-white" title="{{ __('messages.Delete') }}">
                                    <i class="text-xs fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-sm text-center text-secondary">{{ __('messages.No permissions found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($permissions->hasPages())
        <div class="p-4 border-t-2 border-on-surface">
            {{ $permissions->links('vendor.pagination.tailwind') }}
        </div>
    @endif
</div>
