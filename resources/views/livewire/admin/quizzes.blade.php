<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none tracking-[0.08em]">{{ __('messages.Quiz Management') }}</h2>
            <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Manage all quizzes across the platform') }}</p>
        </div>
    </header>

    <div class="p-[24px] max-w-[1400px] mx-auto space-y-6">
        @include('livewire.admin.quizzes-components.tables.quizzes-table')

        @include('livewire.admin.quizzes-components.modals.show-edit-quiz-modal')
        @include('livewire.admin.quizzes-components.modals.show-attempts-modal')
        @include('livewire.admin.quizzes-components.modals.question-modals')
        @include('livewire.admin.quizzes-components.modals.option-modals')
    </div>
</div>
