<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.Users Management') }}</h1>
            <p class="mt-1 text-sm text-gray-500">Manage all platform users and their roles</p>
        </div>
    </div>

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
