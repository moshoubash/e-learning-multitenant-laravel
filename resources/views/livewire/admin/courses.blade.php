<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Courses Management') }}
        </h2>
    </div>
</x-slot>

<div class="max-w-7xl py-6 mx-auto sm:px-6 lg:px-6">
    <!-- Tables -->

    <!-- Courses Table -->
    @include('livewire.admin.courses-components.tables.courses-table')

    <!-- Deleted Courses Section -->
    @include('livewire.admin.courses-components.tables.deleted-courses')


    <!-- Modals -->

    <!-- Create Modal -->
    @include('livewire.admin.courses-components.modals.show-create-modal')

    <!-- Edit Modal -->
    @include('livewire.admin.courses-components.modals.show-edit-modal')

    <!-- Delete Confirmation Modal -->
    @include('livewire.admin.courses-components.modals.show-delete-modal')

    <!-- Restore Confirmation Modal -->
    @include('livewire.admin.courses-components.modals.show-restore-modal')
</div>