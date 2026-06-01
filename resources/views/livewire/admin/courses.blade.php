<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.Courses Management') }}</h1>
            <p class="mt-1 text-sm text-gray-500">Manage all courses across the platform</p>
        </div>
    </div>

    <!-- Tables -->
    @include('livewire.admin.courses-components.tables.courses-table')

    @include('livewire.admin.courses-components.tables.deleted-courses')

    <!-- Modals -->
    @include('livewire.admin.courses-components.modals.show-create-modal')
    @include('livewire.admin.courses-components.modals.show-edit-modal')
    @include('livewire.admin.courses-components.modals.show-delete-modal')
    @include('livewire.admin.courses-components.modals.show-restore-modal')
</div>
