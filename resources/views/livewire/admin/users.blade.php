<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users Management') }}
        </h2>
    </div>
</x-slot>

<div class="max-w-7xl py-6 mx-auto sm:px-6 lg:px-6">
    <!-- Tables -->

    <!-- Users Table -->
    @include('livewire.admin.users-components.tables.users-table')

    <!-- Deleted Users Section -->
    @include('livewire.admin.users-components.tables.deleted-users')


    <!-- Modals -->

    <!-- Create Modal -->
    @include('livewire.admin.users-components.modals.show-create-modal')

    <!-- Edit Modal -->
    @include('livewire.admin.users-components.modals.show-edit-modal')

    <!-- Delete Confirmation Modal -->
    @include('livewire.admin.users-components.modals.show-delete-modal')

    <!-- Restore Confirmation Modal -->
    @include('livewire.admin.users-components.modals.show-restore-users-modal')
</div>