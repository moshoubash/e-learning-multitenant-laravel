<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quiz Management') }}
        </h2>
    </div>
</x-slot>

<div class="max-w-7xl py-6 mx-auto sm:px-6 lg:px-6">
    <!-- Quizzes Table -->
    @include('livewire.admin.quizzes-components.tables.quizzes-table')

    <!-- Modals -->
    @include('livewire.admin.quizzes-components.modals.show-edit-quiz-modal')
    @include('livewire.admin.quizzes-components.modals.show-attempts-modal')
    @include('livewire.admin.quizzes-components.modals.question-modals')
    @include('livewire.admin.quizzes-components.modals.option-modals')
</div>