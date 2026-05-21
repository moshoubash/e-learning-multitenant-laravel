<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.Courses Management') }}
        </h2>
    </div>
</x-slot>

<div class="max-w-7xl py-6 mx-auto sm:px-6 lg:px-6">
    <!-- Tables -->
    @include('livewire.admin.courses-components.tables.courses-table')

    <!-- Modals -->
    @include('livewire.admin.courses-components.modals.show-create-modal')
    @include('livewire.admin.courses-components.modals.show-edit-modal')
    @include('livewire.admin.courses-components.modals.show-delete-modal')
</div>
