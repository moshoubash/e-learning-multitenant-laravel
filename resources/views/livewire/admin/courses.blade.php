<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('messages.Courses Management') }}
        </h2>
    </div>
</x-slot>

<div class="py-6 mx-auto max-w-7xl sm:px-6 lg:px-6">
    <!-- Tables -->
    @include('livewire.admin.courses-components.tables.courses-table')

    @include('livewire.admin.courses-components.tables.deleted-courses')

    <!-- Modals -->
    @include('livewire.admin.courses-components.modals.show-create-modal')
    @include('livewire.admin.courses-components.modals.show-edit-modal')
    @include('livewire.admin.courses-components.modals.show-delete-modal')
    @include('livewire.admin.courses-components.modals.show-restore-modal')
</div>
