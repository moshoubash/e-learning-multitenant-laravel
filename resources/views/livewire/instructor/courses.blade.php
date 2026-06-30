<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none">{{ __('messages.Courses Management') }}</h2>
        <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Manage your courses, sections, and lessons') }}</p>
    </div>
    <div class="flex items-center gap-2">
        @livewire('shared.notification-bell')
    </div>
</header>

    <div class="p-[24px] max-w-[1400px] mx-auto space-y-6">
        @include('livewire.instructor.courses-components.tables.courses-table')
        @include('livewire.instructor.courses-components.tables.deleted-courses')

        @include('livewire.instructor.courses-components.modals.show-create-modal')
        @include('livewire.instructor.courses-components.modals.show-edit-modal')
        @include('livewire.instructor.courses-components.modals.show-delete-modal')
        @include('livewire.instructor.courses-components.modals.show-restore-modal')

        @include('livewire.instructor.courses-components.modals.sections.show-create-modal')
        @include('livewire.instructor.courses-components.modals.sections.show-edit-modal')
        @include('livewire.instructor.courses-components.modals.sections.show-delete-modal')
        @include('livewire.instructor.courses-components.modals.sections.show-restore-modal')

        @include('livewire.instructor.courses-components.modals.lessons.show-create-modal')
        @include('livewire.instructor.courses-components.modals.lessons.show-edit-modal')
        @include('livewire.instructor.courses-components.modals.lessons.show-delete-modal')
        @include('livewire.instructor.courses-components.modals.lessons.show-restore-modal')
        @include('livewire.instructor.courses-components.modals.lessons.show-playlist-import-modal')

        @include('livewire.instructor.courses-components.modals.assignments.show-create-modal')
        @include('livewire.instructor.courses-components.modals.assignments.show-edit-modal')
        @include('livewire.instructor.courses-components.modals.assignments.show-delete-modal')
        @include('livewire.instructor.courses-components.modals.assignments.show-restore-modal')

        @include('livewire.instructor.courses-components.modals.quizzes.show-create-modal')
        @include('livewire.instructor.courses-components.modals.quizzes.show-edit-modal')
        @include('livewire.instructor.courses-components.modals.quizzes.show-delete-modal')
    </div>
</div>
