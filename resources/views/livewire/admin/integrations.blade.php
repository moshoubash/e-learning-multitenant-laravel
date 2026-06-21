<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none tracking-[0.08em]">{{ __('messages.Integrations') }}</h2>
            <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Manage OAuth providers and API credentials') }}</p>
        </div>
    <div class="flex items-center gap-2">
        @livewire('shared.notification-bell')
        <button wire:click="openCreateModal"
                class="px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
                <i class="fas fa-plus ltr:mr-2 rtl:ml-2"></i>
                {{ __('messages.Add Integration') }}
            </button>
        </div>
    </header>

    <div class="p-[24px] max-w-[1400px] mx-auto space-y-6">
        {{-- Integrations Table --}}
        <div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
            <div class="overflow-x-auto">
                <table class="w-full ltr:text-left rtl:text-right">
                    <thead class="border-b-2 bg-surface-container-low border-on-surface">
                        <tr>
                            <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Provider') }}</th>
                            <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Client ID') }}</th>
                            <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Redirect URL') }}</th>
                            <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Status') }}</th>
                            <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E5E5]">
                        @forelse ($integrations as $integration)
                            <tr class="transition-colors duration-150 hover:bg-surface-container-low">
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        @if($integration->provider === 'google')
                                            <i class="fab fa-google text-on-surface"></i>
                                        @endif
                                        <span class="text-sm font-bold text-on-surface">{{ ucfirst($integration->provider) }}</span>
                                    </div>
                                </td>
                                <td class="p-4 font-mono text-sm text-on-surface">{{ $integration->client_id }}</td>
                                <td class="p-4 text-sm text-on-surface">{{ $integration->redirect_url }}</td>
                                <td class="p-4">
                                    <button wire:click="toggleActive({{ $integration->id }})"
                                        class="px-2.5 py-1 neo-border-sm neo-radius text-[10px] font-bold transition-colors {{ $integration->is_active ? 'bg-primary-container text-on-primary-container' : 'bg-surface-container text-secondary' }}">
                                        {{ $integration->is_active ? __('messages.Active') : __('messages.Inactive') }}
                                    </button>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <button wire:click="openEditModal({{ $integration->id }})"
                                            class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Edit">
                                            <i class="text-xs fas fa-edit"></i>
                                        </button>
                                        <button wire:click="openDeleteModal({{ $integration->id }})"
                                            class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-error hover:bg-error hover:text-white" title="Delete">
                                            <i class="text-xs fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-sm text-center text-secondary">{{ __('messages.No integrations found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t-2 border-on-surface">
                {{ $integrations->links() }}
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-on-surface/60" wire:click="closeModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="inline-block overflow-hidden align-bottom transition-all transform ltr:text-left rtl:text-right bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="mb-4 text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Add Integration') }}</h3>
                        <form>
                            <div class="mb-4">
                                <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Provider') }}</label>
                                <select wire:model.lazy="createProvider"
                                    class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0">
                                    <option value="google">Google</option>
                                </select>
                                @error('createProvider') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-4">
                                <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Client ID') }}</label>
                                <input type="text" wire:model.lazy="createClientId"
                                    class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary">
                                @error('createClientId') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-4">
                                <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Client Secret') }}</label>
                                <input type="password" wire:model.lazy="createClientSecret"
                                    class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary">
                                @error('createClientSecret') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-4">
                                <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Redirect URL') }}</label>
                                <input type="url" wire:model.lazy="createRedirectUrl"
                                    class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary">
                                @error('createRedirectUrl') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model.lazy="createIsActive"
                                        class="w-4 h-4 neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 ltr:mr-2 rtl:ml-2">
                                    <span class="text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Active') }}</span>
                                </label>
                            </div>
                        </form>
                    </div>
                    <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="store" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                            {{ __('messages.Create') }}
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

    {{-- Edit Modal --}}
    @if($showEditModal && $editingIntegration)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-on-surface/60" wire:click="closeModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="inline-block overflow-hidden align-bottom transition-all transform ltr:text-left rtl:text-right bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="mb-4 text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Edit Integration') }}: {{ ucfirst($editingIntegration->provider) }}</h3>
                        <form>
                            <div class="mb-4">
                                <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Client ID') }}</label>
                                <input type="text" wire:model.lazy="editClientId"
                                    class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary">
                                @error('editClientId') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-4">
                                <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Client Secret') }}</label>
                                <input type="password" wire:model.lazy="editClientSecret"
                                    class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary">
                                @error('editClientSecret') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-4">
                                <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Redirect URL') }}</label>
                                <input type="url" wire:model.lazy="editRedirectUrl"
                                    class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary">
                                @error('editRedirectUrl') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model.lazy="editIsActive"
                                        class="w-4 h-4 neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 ltr:mr-2 rtl:ml-2">
                                    <span class="text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Active') }}</span>
                                </label>
                            </div>
                        </form>
                    </div>
                    <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="update" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                            {{ __('messages.Update') }}
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

    {{-- Delete Modal --}}
    @if($showDeleteModal && $deletingIntegration)
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
                            <div class="mt-3 text-center sm:mt-0 sm:ltr:ml-4 sm:rtl:mr-4 sm:ltr:text-left rtl:text-right">
                                <h3 class="text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Delete Integration') }}</h3>
                                <p class="mt-2 text-sm text-secondary">
                                    {{ __('messages.Are you sure you want to delete the :provider integration?', ['provider' => ucfirst($deletingIntegration->provider)]) }}
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
