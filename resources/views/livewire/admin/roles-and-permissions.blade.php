<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none">{{ __('messages.Roles & Permissions') }}</h2>
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

        @if($activeTab === 'roles')
            @include('livewire.admin.roles-and-permissions-components.tables.roles-table')
        @endif

        @if($activeTab === 'permissions')
            @include('livewire.admin.roles-and-permissions-components.tables.permissions-table')
        @endif
    </div>

    @include('livewire.admin.roles-and-permissions-components.modals.create-role-modal')
    @include('livewire.admin.roles-and-permissions-components.modals.edit-role-modal')
    @include('livewire.admin.roles-and-permissions-components.modals.delete-role-modal')
    @include('livewire.admin.roles-and-permissions-components.modals.assign-permissions-modal')
    @include('livewire.admin.roles-and-permissions-components.modals.create-permission-modal')
    @include('livewire.admin.roles-and-permissions-components.modals.edit-permission-modal')
    @include('livewire.admin.roles-and-permissions-components.modals.delete-permission-modal')
</div>
