<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none tracking-[0.08em]">{{ __('messages.Roles & Permissions') }}</h2>
            <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Manage user roles and permissions') }}</p>
        </div>
        <div class="flex items-center gap-2">
            @livewire('shared.notification-bell')
        </div>
    </header>

    <div class="p-[24px] max-w-[1400px] mx-auto space-y-6">
        {{-- Tabs --}}
        <div class="flex overflow-hidden border-2 neo-border neo-radius bg-surface-container-lowest border-on-surface">
            <button wire:click="switchTab('roles')"
                class="flex-1 py-3 text-xs font-bold tracking-widest uppercase transition-colors {{ $activeTab === 'roles' ? 'bg-primary-container text-on-primary-container' : 'bg-surface-container-lowest text-secondary hover:bg-surface-container-high' }}">
                <i class="fas fa-user-tag ltr:mr-2 rtl:ml-2"></i>
                {{ __('messages.Roles') }}
            </button>
            <button wire:click="switchTab('permissions')"
                class="flex-1 py-3 text-xs font-bold tracking-widest uppercase transition-colors border-l-2 border-r-2 border-on-surface {{ $activeTab === 'permissions' ? 'bg-primary-container text-on-primary-container' : 'bg-surface-container-lowest text-secondary hover:bg-surface-container-high' }}">
                <i class="fas fa-key ltr:mr-2 rtl:ml-2"></i>
                {{ __('messages.Permissions') }}
            </button>
        </div>

        {{-- Roles Tab --}}
        @if($activeTab === 'roles')
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
                                            @if($role->name !== 'admin')
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
        @endif

        {{-- Permissions Tab --}}
        @if($activeTab === 'permissions')
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
        @endif
    </div>

    {{-- Create Role Modal --}}
    @if($showRoleCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-on-surface/60" wire:click="closeModals"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="inline-block overflow-hidden align-bottom transition-all transform ltr:text-left rtl:text-right bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="mb-4 text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Create Role') }}</h3>
                        <form>
                            <div class="mb-4">
                                <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Role Name') }}</label>
                                <input type="text" wire:model.lazy="roleName" placeholder="e.g. manager, ta, reviewer"
                                    class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary">
                                @error('roleName') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                            </div>
                        </form>
                    </div>
                    <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="storeRole" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                            {{ __('messages.Create') }}
                        </button>
                        <button wire:click="closeModals" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-xs font-bold transition-colors neo-border-sm neo-radius text-on-surface bg-surface-container hover:bg-on-surface hover:text-white sm:mt-0 sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                            {{ __('messages.Cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Edit Role Modal --}}
    @if($showRoleEditModal && $editingRole)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-on-surface/60" wire:click="closeModals"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="inline-block overflow-hidden align-bottom transition-all transform ltr:text-left rtl:text-right bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="mb-4 text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Edit Role') }}: {{ ucfirst($editingRole->name) }}</h3>
                        <form>
                            <div class="mb-4">
                                <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Role Name') }}</label>
                                <input type="text" wire:model.lazy="roleEditName"
                                    class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary">
                                @error('roleEditName') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                            </div>
                        </form>
                    </div>
                    <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="updateRole" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                            {{ __('messages.Update') }}
                        </button>
                        <button wire:click="closeModals" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-xs font-bold transition-colors neo-border-sm neo-radius text-on-surface bg-surface-container hover:bg-on-surface hover:text-white sm:mt-0 sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                            {{ __('messages.Cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Role Modal --}}
    @if($showRoleDeleteModal && $deletingRole)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-on-surface/60" wire:click="closeModals"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="inline-block overflow-hidden align-bottom transition-all transform ltr:text-left rtl:text-right bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="flex items-center justify-center w-12 h-12 mx-auto neo-border-sm neo-radius bg-error/10 shrink-0 sm:mx-0">
                                <i class="text-error fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ltr:ml-4 sm:rtl:mr-4 sm:ltr:text-left rtl:text-right">
                                <h3 class="text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Delete Role') }}</h3>
                                <p class="mt-2 text-sm text-secondary">
                                    {{ __('messages.Are you sure you want to delete the :role role?', ['role' => ucfirst($deletingRole->name)]) }}
                                </p>
                                @if($deletingRole->users->count() > 0)
                                    <p class="mt-2 text-xs font-bold text-error">
                                        <i class="fas fa-exclamation-circle ltr:mr-1 rtl:ml-1"></i>
                                        {{ __('messages.This role has :count users assigned.', ['count' => $deletingRole->users->count()]) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="deleteRole" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 text-xs font-bold tracking-widest text-white uppercase transition-colors neo-border neo-radius bg-error hover:bg-on-surface sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                            {{ __('messages.Delete') }}
                        </button>
                        <button wire:click="closeModals" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-xs font-bold transition-colors neo-border-sm neo-radius text-on-surface bg-surface-container hover:bg-on-surface hover:text-white sm:mt-0 sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                            {{ __('messages.Cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Assign Permissions Modal --}}
    @if($showAssignModal && $assigningRole)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-on-surface/60" wire:click="closeModals"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="inline-block overflow-hidden align-bottom transition-all transform ltr:text-left rtl:text-right bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="mb-1 text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Assign Permissions') }}</h3>
                        <p class="mb-4 text-xs text-secondary">{{ __('messages.Select permissions for the :role role.', ['role' => ucfirst($assigningRole->name)]) }}</p>
                        <div class="space-y-2 overflow-y-auto max-h-96">
                            @foreach(Spatie\Permission\Models\Permission::where('guard_name', 'tenant')->orderBy('name')->get() as $permission)
                                <label class="flex items-center p-3 transition-colors cursor-pointer neo-border-sm neo-radius bg-surface-container-low hover:bg-surface-container-high">
                                    <input type="checkbox" value="{{ $permission->id }}"
                                        wire:model.lazy="assignedPermissions"
                                        class="w-4 h-4 neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 ltr:mr-3 rtl:ml-3">
                                    <span class="text-sm font-medium text-on-surface">{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="savePermissions" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                            {{ __('messages.Save') }}
                        </button>
                        <button wire:click="closeModals" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-xs font-bold transition-colors neo-border-sm neo-radius text-on-surface bg-surface-container hover:bg-on-surface hover:text-white sm:mt-0 sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                            {{ __('messages.Cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Create Permission Modal --}}
    @if($showPermissionCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-on-surface/60" wire:click="closeModals"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="inline-block overflow-hidden align-bottom transition-all transform ltr:text-left rtl:text-right bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="mb-4 text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Create Permission') }}</h3>
                        <form>
                            <div class="mb-4">
                                <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Permission Name') }}</label>
                                <input type="text" wire:model.lazy="permissionName" placeholder="e.g. view reports, manage users"
                                    class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary">
                                @error('permissionName') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                            </div>
                        </form>
                    </div>
                    <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="storePermission" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                            {{ __('messages.Create') }}
                        </button>
                        <button wire:click="closeModals" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-xs font-bold transition-colors neo-border-sm neo-radius text-on-surface bg-surface-container hover:bg-on-surface hover:text-white sm:mt-0 sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                            {{ __('messages.Cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Edit Permission Modal --}}
    @if($showPermissionEditModal && $editingPermission)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-on-surface/60" wire:click="closeModals"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="inline-block overflow-hidden align-bottom transition-all transform ltr:text-left rtl:text-right bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="mb-4 text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Edit Permission') }}: {{ $editingPermission->name }}</h3>
                        <form>
                            <div class="mb-4">
                                <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Permission Name') }}</label>
                                <input type="text" wire:model.lazy="permissionEditName"
                                    class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary">
                                @error('permissionEditName') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                            </div>
                        </form>
                    </div>
                    <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="updatePermission" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                            {{ __('messages.Update') }}
                        </button>
                        <button wire:click="closeModals" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-xs font-bold transition-colors neo-border-sm neo-radius text-on-surface bg-surface-container hover:bg-on-surface hover:text-white sm:mt-0 sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                            {{ __('messages.Cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Permission Modal --}}
    @if($showPermissionDeleteModal && $deletingPermission)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-on-surface/60" wire:click="closeModals"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="inline-block overflow-hidden align-bottom transition-all transform ltr:text-left rtl:text-right bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="flex items-center justify-center w-12 h-12 mx-auto neo-border-sm neo-radius bg-error/10 shrink-0 sm:mx-0">
                                <i class="text-error fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ltr:ml-4 sm:rtl:mr-4 sm:ltr:text-left rtl:text-right">
                                <h3 class="text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Delete Permission') }}</h3>
                                <p class="mt-2 text-sm text-secondary">
                                    {{ __('messages.Are you sure you want to delete the :permission permission?', ['permission' => $deletingPermission->name]) }}
                                </p>
                                @if($deletingPermission->roles->count() > 0)
                                    <p class="mt-2 text-xs font-bold text-warning">
                                        <i class="fas fa-exclamation-circle ltr:mr-1 rtl:ml-1"></i>
                                        {{ __('messages.This permission is assigned to :count roles.', ['count' => $deletingPermission->roles->count()]) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="deletePermission" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 text-xs font-bold tracking-widest text-white uppercase transition-colors neo-border neo-radius bg-error hover:bg-on-surface sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                            {{ __('messages.Delete') }}
                        </button>
                        <button wire:click="closeModals" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-xs font-bold transition-colors neo-border-sm neo-radius text-on-surface bg-surface-container hover:bg-on-surface hover:text-white sm:mt-0 sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                            {{ __('messages.Cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
