<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none tracking-[0.08em]">{{ __('messages.Quizzes Management') }}</h2>
            <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Create and manage quizzes for your courses') }}</p>
        </div>
    </header>

    <div class="p-[24px] max-w-[1400px] mx-auto space-y-6">
        @include('livewire.instructor.quizzes-components.tables.quizzes-table')

        @include('livewire.instructor.quizzes-components.modals.quizzes.show-create-modal')
        @include('livewire.instructor.quizzes-components.modals.quizzes.show-edit-modal')
        @include('livewire.instructor.quizzes-components.modals.quizzes.show-delete-modal')
        @include('livewire.instructor.quizzes-components.modals.quizzes.show-attempts-modal')

        @include('livewire.instructor.quizzes-components.modals.questions.show-create-modal')
        @include('livewire.instructor.quizzes-components.modals.questions.show-edit-modal')
        @include('livewire.instructor.quizzes-components.modals.questions.show-delete-modal')

        @include('livewire.instructor.quizzes-components.modals.options.show-create-modal')
        @include('livewire.instructor.quizzes-components.modals.options.show-edit-modal')
        @include('livewire.instructor.quizzes-components.modals.options.show-delete-modal')
    </div>
</div>