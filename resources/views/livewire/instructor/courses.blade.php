<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.Courses Management') }}
        </h2>
    </div>
</x-slot>

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
@endpush

<div class="max-w-7xl py-6 mx-auto sm:px-6 lg:px-6">
    <!-- Tables -->

    <!-- Courses Table with Curriculum -->
    @include('livewire.instructor.courses-components.tables.courses-table')

    <!-- Deleted Courses Section -->
    @include('livewire.instructor.courses-components.tables.deleted-courses')


    <!-- COURSE MODALS -->

    <!-- Create Modal -->
    @include('livewire.instructor.courses-components.modals.show-create-modal')

    <!-- Edit Modal -->
    @include('livewire.instructor.courses-components.modals.show-edit-modal')

    <!-- Delete Confirmation Modal -->
    @include('livewire.instructor.courses-components.modals.show-delete-modal')

    <!-- Restore Confirmation Modal -->
    @include('livewire.instructor.courses-components.modals.show-restore-modal')


    <!-- SECTION MODALS -->

    <!-- Section Create Modal -->
    @include('livewire.instructor.courses-components.modals.sections.show-create-modal')

    <!-- Section Edit Modal -->
    @include('livewire.instructor.courses-components.modals.sections.show-edit-modal')

    <!-- Section Delete Confirmation Modal -->
    @include('livewire.instructor.courses-components.modals.sections.show-delete-modal')

    <!-- Section Restore Confirmation Modal -->
    @include('livewire.instructor.courses-components.modals.sections.show-restore-modal')


    <!-- LESSON MODALS -->

    <!-- Lesson Create Modal -->
    @include('livewire.instructor.courses-components.modals.lessons.show-create-modal')

    <!-- Lesson Edit Modal -->
    @include('livewire.instructor.courses-components.modals.lessons.show-edit-modal')

    <!-- Lesson Delete Confirmation Modal -->
    @include('livewire.instructor.courses-components.modals.lessons.show-delete-modal')

    <!-- Lesson Restore Confirmation Modal -->
    @include('livewire.instructor.courses-components.modals.lessons.show-restore-modal')

    <!-- ASSIGNMENT MODALS -->

    <!-- Assignment Create Modal -->
    @include('livewire.instructor.courses-components.modals.assignments.show-create-modal')

    <!-- Assignment Edit Modal -->
    @include('livewire.instructor.courses-components.modals.assignments.show-edit-modal')

    <!-- Assignment Delete Confirmation Modal -->
    @include('livewire.instructor.courses-components.modals.assignments.show-delete-modal')

    <!-- Assignment Restore Confirmation Modal -->
    @include('livewire.instructor.courses-components.modals.assignments.show-restore-modal')


    <!-- QUIZ MODALS -->

    <!-- Quiz Create Modal -->
    @include('livewire.instructor.courses-components.modals.quizzes.show-create-modal')

    <!-- Quiz Edit Modal -->
    @include('livewire.instructor.courses-components.modals.quizzes.show-edit-modal')

    <!-- Quiz Delete Confirmation Modal -->
    @include('livewire.instructor.courses-components.modals.quizzes.show-delete-modal')
</div>
