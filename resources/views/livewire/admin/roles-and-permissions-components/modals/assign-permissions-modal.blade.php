@if($showAssignModal && $assigningRole)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-on-surface/60" wire:click="closeModals"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block w-full overflow-hidden align-bottom transition-all transform ltr:text-left rtl:text-right bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="mb-1 text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Assign Permissions') }}</h3>
                    <p class="mb-4 text-xs text-secondary">{{ __('messages.Select permissions for the :role role.', ['role' => ucfirst($assigningRole->name)]) }}</p>
                    <div class="flex items-center gap-2 mb-3">
                        <button wire:click="selectAllPermissions" type="button"
                            class="px-3 py-1 text-[10px] font-bold tracking-widest uppercase transition-colors neo-border-sm neo-radius text-on-surface bg-surface-container hover:bg-on-surface hover:text-white">
                            {{ __('messages.Select All') }}
                        </button>
                        <button wire:click="deselectAllPermissions" type="button"
                            class="px-3 py-1 text-[10px] font-bold tracking-widest uppercase transition-colors neo-border-sm neo-radius text-on-surface bg-surface-container hover:bg-on-surface hover:text-white">
                            {{ __('messages.Deselect All') }}
                        </button>
                    </div>
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
