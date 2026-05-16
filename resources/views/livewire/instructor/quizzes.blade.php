<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quizzes Management') }}
        </h2>
    </div>
</x-slot>

<div class="max-w-7xl py-6 mx-auto sm:px-6 lg:px-6">
    <!-- Tables -->
    @include('livewire.instructor.quizzes-components.tables.quizzes-table')

    <!--  QUIZ MODALS  -->
    @include('livewire.instructor.quizzes-components.modals.quizzes.show-create-modal')
    @include('livewire.instructor.quizzes-components.modals.quizzes.show-edit-modal')
    @include('livewire.instructor.quizzes-components.modals.quizzes.show-delete-modal')

    <!--  QUESTION MODALS  -->
    @include('livewire.instructor.quizzes-components.modals.questions.show-create-modal')
    @include('livewire.instructor.quizzes-components.modals.questions.show-edit-modal')
    @include('livewire.instructor.quizzes-components.modals.questions.show-delete-modal')

    <!--  OPTION MODALS  -->
    @include('livewire.instructor.quizzes-components.modals.options.show-create-modal')
    @include('livewire.instructor.quizzes-components.modals.options.show-edit-modal')
    @include('livewire.instructor.quizzes-components.modals.options.show-delete-modal')
</div>