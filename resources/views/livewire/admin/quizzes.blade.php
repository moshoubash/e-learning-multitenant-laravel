<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.Quiz Management') }}</h1>
            <p class="mt-1 text-sm text-gray-500">Manage all quizzes across the platform</p>
        </div>
    </div>

    <!-- Quizzes Table -->
    @include('livewire.admin.quizzes-components.tables.quizzes-table')

    <!-- Modals -->
    @include('livewire.admin.quizzes-components.modals.show-edit-quiz-modal')
    @include('livewire.admin.quizzes-components.modals.show-attempts-modal')
    @include('livewire.admin.quizzes-components.modals.question-modals')
    @include('livewire.admin.quizzes-components.modals.option-modals')
</div>
