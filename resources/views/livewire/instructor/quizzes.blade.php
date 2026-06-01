<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.Quizzes Management') }}</h1>
            <p class="mt-1 text-sm text-gray-500">{{ __('messages.Create and manage quizzes for your courses') }}</p>
        </div>
    </div>

    <!-- Tables -->
    @include('livewire.instructor.quizzes-components.tables.quizzes-table')

    <!--  QUIZ MODALS  -->
    @include('livewire.instructor.quizzes-components.modals.quizzes.show-create-modal')
    @include('livewire.instructor.quizzes-components.modals.quizzes.show-edit-modal')
    @include('livewire.instructor.quizzes-components.modals.quizzes.show-delete-modal')
    @include('livewire.instructor.quizzes-components.modals.quizzes.show-attempts-modal')

    <!--  QUESTION MODALS  -->
    @include('livewire.instructor.quizzes-components.modals.questions.show-create-modal')
    @include('livewire.instructor.quizzes-components.modals.questions.show-edit-modal')
    @include('livewire.instructor.quizzes-components.modals.questions.show-delete-modal')

    <!--  OPTION MODALS  -->
    @include('livewire.instructor.quizzes-components.modals.options.show-create-modal')
    @include('livewire.instructor.quizzes-components.modals.options.show-edit-modal')
    @include('livewire.instructor.quizzes-components.modals.options.show-delete-modal')
</div>
