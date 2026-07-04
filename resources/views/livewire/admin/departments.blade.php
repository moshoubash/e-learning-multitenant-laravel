<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none">{{ __('messages.Departments Management') }}</h2>
            <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Organize users and courses by department') }}</p>
        </div>
        <div class="flex items-center gap-2">
            @livewire('shared.notification-bell')
        </div>
    </header>

    <div class="p-[24px] max-w-[1400px] mx-auto space-y-6">
        @include('livewire.admin.departments-components.tables.departments-table')
        @include('livewire.admin.departments-components.tables.deleted-departments')

        @include('livewire.admin.departments-components.modals.show-create-modal')
        @include('livewire.admin.departments-components.modals.show-edit-modal')
        @include('livewire.admin.departments-components.modals.show-delete-modal')
        @include('livewire.admin.departments-components.modals.show-restore-modal')
    </div>
</div>
