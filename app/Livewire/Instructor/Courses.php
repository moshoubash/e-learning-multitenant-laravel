<?php

namespace App\Livewire\Instructor;

use App\Models\Tenant\Course;
use App\Models\Tenant\Section;
use App\Models\Tenant\Assignment;
use App\Models\Tenant\AssignmentAttachment;
use App\Models\Tenant\Lesson;
use App\Models\Tenant\User;
use App\Notifications\NewCoursePublished;
use App\Services\Instructor\AssignmentService;
use App\Services\Instructor\CourseService;
use App\Services\Instructor\LessonService;
use App\Services\Instructor\QuizService;
use App\Services\Instructor\SectionService;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

#[Layout('layouts.instructor')]
class Courses extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'tailwind';

    // Course modals
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showRestoreModal = false;

    // Section modals
    public $showSectionCreateModal = false;
    public $showSectionEditModal = false;
    public $showSectionDeleteModal = false;
    public $showSectionRestoreModal = false;

    // Lesson modals
    public $showLessonCreateModal = false;
    public $showLessonEditModal = false;
    public $showLessonDeleteModal = false;
    public $showLessonRestoreModal = false;

    // Quiz modals
    public $showQuizCreateModal = false;
    public $showQuizEditModal = false;
    public $showQuizDeleteModal = false;

    // Assignment modals
    public $showAssignmentCreateModal = false;
    public $showAssignmentEditModal = false;
    public $showAssignmentDeleteModal = false;
    public $showAssignmentRestoreModal = false;

    // Selected items
    public $editingCourse = null;
    public $deletingCourse = null;
    public $restoringCourse = null;

    public $editingSection = null;
    public $deletingSection = null;
    public $restoringSection = null;

    public $editingLesson = null;
    public $deletingLesson = null;
    public $restoringLesson = null;

    public $editingQuiz = null;
    public $deletingQuiz = null;

    public $editingAssignment = null;
    public $deletingAssignment = null;
    public $restoringAssignment = null;

    // Course selection for sections/lessons
    public $selectedCourseId = null;
    public $selectedSectionId = null;

    // Expanded courses tracking
    public $expandedCourses = [];

    // Course create form fields
    public $createTitle = '';
    public $createSlug = '';
    public $createDescription = '';
    public $createPrice = '';
    public $createStatus = 'draft';
    public $createInstructorId = '';

    // Course edit form fields
    public $editTitle = '';
    public $editSlug = '';
    public $editDescription = '';
    public $editPrice = '';
    public $editStatus = '';
    public $editInstructorId = '';

    // Section create form fields
    public $sectionCreateTitle = '';
    public $sectionCreateOrder = 0;

    // Section edit form fields
    public $sectionEditTitle = '';
    public $sectionEditOrder = 0;

    // Lesson create form fields
    public $lessonCreateTitle = '';
    public $lessonCreateType = 'video';
    public $lessonCreateContent = '';
    public $lessonCreateDuration = 0;
    public $lessonCreateOrder = 0;
    public $lessonCreateVideoUrl = '';
    public $courseVideo = null;

    // Assignment create form fields
    public $assignmentCreateTitle = '';
    public $assignmentCreateDescription = '';
    public $assignmentCreateInstructions = '';
    public $assignmentCreateDueDate = '';
    public $assignmentCreateMaxScore = 100;
    public $assignmentCreateAllowLate = 1;
    public $assignmentCreateStatus = 'draft';
    public $assignmentCreateOrder = 0;
    public $assignmentCreateAttachments = null;

    // Lesson edit form fields
    public $lessonEditTitle = '';
    public $lessonEditType = '';
    public $lessonEditContent = '';
    public $lessonEditDuration = 0;
    public $lessonEditOrder = 0;
    public $lessonEditVideoUrl = '';

    // Assignment edit form fields
    public $assignmentEditTitle = '';
    public $assignmentEditDescription = '';
    public $assignmentEditInstructions = '';
    public $assignmentEditDueDate = '';
    public $assignmentEditMaxScore = 100;
    public $assignmentEditAllowLate = 1;
    public $assignmentEditStatus = 'draft';
    public $assignmentEditOrder = 0;
    public $assignmentEditAttachments = null;

    // Quiz create form fields
    public $quizCreateTitle = '';
    public $quizCreatePassPercentage = 70;
    public $quizCreateCanReattempt = false;
    public $quizCreateMaxAttempts = 1;

    // Quiz edit form fields
    public $quizEditTitle = '';
    public $quizEditPassPercentage = 70;
    public $quizEditCanReattempt = false;
    public $quizEditMaxAttempts = 1;

    // max order in sections
    public $maxOrderInSections = 0;
    public $maxOrderInLessons = 0;
    public $maxOrderInAssignments = 0;

    public function mount()
    {
        $this->createInstructorId = auth()->user()->id;
        $this->editInstructorId = auth()->user()->id;

        $this->maxOrderInSections = Section::max('order');
        $this->maxOrderInLessons = Lesson::max('order');
        $this->maxOrderInAssignments = Assignment::max('order');
    }

    // Auto-generate slug from title
    public function updatedCreateTitle($value)
    {
        $this->createSlug = Str::slug($value);
    }

    public function updatedEditTitle($value)
    {
        $this->editSlug = Str::slug($value);
    }

    // Toggle course expansion
    public function toggleCourseExpand($courseId)
    {
        if (in_array($courseId, $this->expandedCourses)) {
            $this->expandedCourses = array_filter($this->expandedCourses, fn($id) => $id !== $courseId);
        } else {
            $this->expandedCourses[] = $courseId;
        }
    }

    public function isCourseExpanded($courseId)
    {
        return in_array($courseId, $this->expandedCourses);
    }

    //  COURSE METHODS

    public function openCreateModal()
    {
        // $this->resetCreateForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($id)
    {
        $this->editingCourse = $this->courseService()->findById($id);

        if (! $this->editingCourse) {
            Toaster::error('Course not found.');
            return;
        }

        $this->editTitle = $this->editingCourse->title;
        $this->editSlug = $this->editingCourse->slug;
        $this->editDescription = $this->editingCourse->description;
        $this->editPrice = $this->editingCourse->price;
        $this->editStatus = $this->editingCourse->status;
        $this->editInstructorId = $this->editingCourse->instructor_id;
        $this->showEditModal = true;
    }

    public function openDeleteModal($id)
    {
        $this->deletingCourse = $this->courseService()->findById($id);
        $this->showDeleteModal = true;
    }

    public function openRestoreModal($id)
    {
        $this->restoringCourse = $this->courseService()->findWithTrashed($id);
        $this->showRestoreModal = true;
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->showRestoreModal = false;
        $this->resetFormFields();
    }

    public function resetCreateForm()
    {
        $this->createTitle = '';
        $this->createSlug = '';
        $this->createDescription = '';
        $this->createPrice = '';
        $this->createStatus = 'draft';
        $this->createInstructorId = '';
    }

    public function resetFormFields()
    {
        $this->editingCourse = null;
        $this->deletingCourse = null;
        $this->restoringCourse = null;
        $this->editTitle = '';
        $this->editSlug = '';
        $this->editDescription = '';
        $this->editPrice = '';
        $this->editStatus = '';
        $this->editInstructorId = '';
    }

    public function store()
    {
        $this->validate($this->courseCreateRules());

        $course = $this->courseService()->createCourse([
            'title' => $this->createTitle,
            'slug' => $this->createSlug,
            'description' => $this->createDescription,
            'price' => $this->createPrice,
            'status' => $this->createStatus,
            'instructor_id' => $this->createInstructorId,
        ]);

        if ($course->status === 'published') {
            $this->notifyStudentsNewCourse($course);
        }

        $this->closeModal();
        Toaster::success('Course created successfully!');
    }

    public function update()
    {
        $this->validate($this->courseUpdateRules());

        if (! $this->editingCourse) {
            Toaster::error('Course not found.');
            return;
        }

        $oldStatus = $this->editingCourse->status;

        $this->courseService()->updateCourse($this->editingCourse, [
            'title' => $this->editTitle,
            'slug' => $this->editSlug,
            'description' => $this->editDescription,
            'price' => $this->editPrice,
            'status' => $this->editStatus,
            'instructor_id' => $this->editInstructorId,
        ]);

        if ($oldStatus !== 'published' && $this->editStatus === 'published') {
            $course = Course::find($this->editingCourse->id);
            if ($course) {
                $this->notifyStudentsNewCourse($course);
            }
        }

        $this->closeModal();
        Toaster::success('Course updated successfully!');
    }

    protected function notifyStudentsNewCourse(Course $course): void
    {
        $students = User::role('student')->get();
        foreach ($students as $student) {
            $student->notify(new NewCoursePublished($course));
        }
    }

    public function softDelete()
    {
        if ($this->deletingCourse) {
            $this->courseService()->softDeleteCourse($this->deletingCourse);
            $this->closeModal();
            Toaster::success('Course soft deleted successfully!');
        }
    }

    public function restore()
    {
        if ($this->restoringCourse) {
            $this->courseService()->restoreCourse($this->restoringCourse);
            $this->closeModal();
            Toaster::success('Course restored successfully!');
        }
    }

    protected function courseService(): CourseService
    {
        return new CourseService();
    }

    //  SECTION METHODS

    public function openSectionCreateModal($courseId)
    {
        $this->selectedCourseId = $courseId;
        $this->resetSectionCreateForm();
        $this->showSectionCreateModal = true;
    }

    public function openSectionEditModal($id)
    {
        $this->editingSection = $this->sectionService()->findById($id);

        if (! $this->editingSection) {
            Toaster::error('Section not found.');
            return;
        }

        $this->sectionEditTitle = $this->editingSection->title;
        $this->sectionEditOrder = $this->editingSection->order;
        $this->showSectionEditModal = true;
    }

    public function openSectionDeleteModal($id)
    {
        $this->deletingSection = $this->sectionService()->findById($id);
        $this->showSectionDeleteModal = true;
    }

    public function openSectionRestoreModal($id)
    {
        $this->restoringSection = $this->sectionService()->findWithTrashed($id);
        $this->showSectionRestoreModal = true;
    }

    public function closeSectionModal()
    {
        $this->showSectionCreateModal = false;
        $this->showSectionEditModal = false;
        $this->showSectionDeleteModal = false;
        $this->showSectionRestoreModal = false;
        $this->resetSectionFormFields();
    }

    public function resetSectionCreateForm()
    {
        $this->sectionCreateTitle = '';
        $this->sectionCreateOrder = 0;
    }

    public function resetSectionFormFields()
    {
        $this->editingSection = null;
        $this->deletingSection = null;
        $this->restoringSection = null;
        $this->sectionEditTitle = '';
        $this->sectionEditOrder = 0;
    }

    public function storeSection()
    {
        $this->validate($this->sectionCreateRules());

        $this->sectionService()->createSection($this->selectedCourseId, [
            'title' => $this->sectionCreateTitle,
            'order' => $this->sectionCreateOrder,
        ]);

        $this->closeSectionModal();
        Toaster::success('Section created successfully!');
    }

    public function updateSection()
    {
        $this->validate($this->sectionUpdateRules());

        if (! $this->editingSection) {
            Toaster::error('Section not found.');
            return;
        }

        $this->sectionService()->updateSection($this->editingSection, [
            'title' => $this->sectionEditTitle,
            'order' => $this->sectionEditOrder,
        ]);

        $this->closeSectionModal();
        Toaster::success('Section updated successfully!');
    }

    public function softDeleteSection()
    {
        if ($this->deletingSection) {
            $this->sectionService()->softDeleteSection($this->deletingSection);
            $this->closeSectionModal();
            Toaster::success('Section soft deleted successfully!');
        }
    }

    public function restoreSection()
    {
        if ($this->restoringSection) {
            $this->sectionService()->restoreSection($this->restoringSection);
            $this->closeSectionModal();
            Toaster::success('Section restored successfully!');
        }
    }

    protected function sectionService(): SectionService
    {
        return new SectionService();
    }

    //  ASSIGNMENT METHODS

    public function openAssignmentCreateModal($sectionId)
    {
        $this->selectedSectionId = $sectionId;
        $this->resetAssignmentCreateForm();
        $this->showAssignmentCreateModal = true;
    }

    public function openAssignmentEditModal($id)
    {
        $this->editingAssignment = $this->assignmentService()->findByIdWithRelations($id);

        if (! $this->editingAssignment) {
            Toaster::error('Assignment not found.');
            return;
        }

        $this->assignmentEditTitle = $this->editingAssignment->title;
        $this->assignmentEditDescription = $this->editingAssignment->description;
        $this->assignmentEditInstructions = $this->editingAssignment->instructions ?? '';
        $this->assignmentEditDueDate = $this->editingAssignment->due_date ? $this->editingAssignment->due_date->format('Y-m-d\TH:i') : '';
        $this->assignmentEditMaxScore = $this->editingAssignment->max_score ?? 100;
        $this->assignmentEditAllowLate = $this->editingAssignment->allow_late ? 1 : 0;
        $this->assignmentEditStatus = $this->editingAssignment->status ?? 'draft';
        $this->assignmentEditOrder = $this->editingAssignment->order;
        $this->showAssignmentEditModal = true;
    }

    public function openAssignmentDeleteModal($id)
    {
        $this->deletingAssignment = $this->assignmentService()->findById($id);
        $this->showAssignmentDeleteModal = true;
    }

    public function openAssignmentRestoreModal($id)
    {
        $this->restoringAssignment = $this->assignmentService()->findWithTrashed($id);
        $this->showAssignmentRestoreModal = true;
    }

    public function closeAssignmentModal()
    {
        $this->showAssignmentCreateModal = false;
        $this->showAssignmentEditModal = false;
        $this->showAssignmentDeleteModal = false;
        $this->showAssignmentRestoreModal = false;
        $this->resetAssignmentFormFields();
    }

    public function resetAssignmentCreateForm()
    {
        $this->assignmentCreateTitle = '';
        $this->assignmentCreateDescription = '';
        $this->assignmentCreateInstructions = '';
        $this->assignmentCreateDueDate = '';
        $this->assignmentCreateMaxScore = 100;
        $this->assignmentCreateAllowLate = 1;
        $this->assignmentCreateStatus = 'draft';
        $this->assignmentCreateOrder = 0;
        $this->assignmentCreateAttachments = [];
    }

    public function resetAssignmentFormFields()
    {
        $this->editingAssignment = null;
        $this->deletingAssignment = null;
        $this->restoringAssignment = null;
        $this->assignmentCreateTitle = '';
        $this->assignmentCreateDescription = '';
        $this->assignmentCreateInstructions = '';
        $this->assignmentCreateDueDate = '';
        $this->assignmentCreateMaxScore = 100;
        $this->assignmentCreateAllowLate = 1;
        $this->assignmentCreateStatus = 'draft';
        $this->assignmentCreateOrder = 0;
        $this->assignmentCreateAttachments = null;
        $this->assignmentEditTitle = '';
        $this->assignmentEditDescription = '';
        $this->assignmentEditInstructions = '';
        $this->assignmentEditDueDate = '';
        $this->assignmentEditMaxScore = 100;
        $this->assignmentEditAllowLate = 1;
        $this->assignmentEditStatus = 'draft';
        $this->assignmentEditOrder = 0;
        $this->assignmentEditAttachments = null;
    }

    public function storeAssignment()
    {
        $this->validate($this->assignmentCreateRules());

        try {
            $assignment = $this->assignmentService()->createAssignment($this->selectedSectionId, [
                'title' => $this->assignmentCreateTitle,
                'description' => $this->assignmentCreateDescription,
                'instructions' => $this->assignmentCreateInstructions,
                'due_date' => $this->assignmentCreateDueDate ?: null,
                'max_score' => $this->assignmentCreateMaxScore,
                'allow_late' => $this->assignmentCreateAllowLate,
                'status' => $this->assignmentCreateStatus,
                'order' => $this->assignmentCreateOrder,
            ]);

            // Handle attachments
            $this->storeAttachments($assignment, $this->assignmentCreateAttachments);
        } catch (\Throwable $exception) {
            Toaster::error($exception->getMessage());
            $this->closeAssignmentModal();
            return;
        }

        $this->assignmentCreateAttachments = [];
        $this->closeAssignmentModal();
        Toaster::success('Assignment created successfully!');
    }

    public function updateAssignment()
    {
        $this->validate($this->assignmentUpdateRules());

        if (! $this->editingAssignment) {
            Toaster::error('Assignment not found.');
            return;
        }

        try {
            $this->assignmentService()->updateAssignment($this->editingAssignment, [
                'title' => $this->assignmentEditTitle,
                'description' => $this->assignmentEditDescription,
                'instructions' => $this->assignmentEditInstructions,
                'due_date' => $this->assignmentEditDueDate ?: null,
                'max_score' => $this->assignmentEditMaxScore,
                'allow_late' => $this->assignmentEditAllowLate,
                'status' => $this->assignmentEditStatus,
                'order' => $this->assignmentEditOrder,
            ]);

            // Handle new attachments
            if (! empty($this->assignmentEditAttachments)) {
                $this->storeAttachments($this->editingAssignment, $this->assignmentEditAttachments);
                $this->assignmentEditAttachments = [];
            }
        } catch (\Throwable $exception) {
            Toaster::error($exception->getMessage());
            $this->closeAssignmentModal();
            return;
        }

        $this->closeAssignmentModal();
        Toaster::success('Assignment updated successfully!');
    }

    protected function storeAttachments($assignment, $file)
    {
        if (empty($file)) {
            return;
        }

        $tenantId = tenant('id') ?? 'default';
        $baseUrl = 'https://d1w6oovjx4x1vx.cloudfront.net';
        $path = $file->storeAs("assignments/{$tenantId}", $file->getClientOriginalName(), 's3');

        AssignmentAttachment::create([
            'assignment_id' => $assignment->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $baseUrl . '/' . $path,
            'file_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);
    }

    public function removeAttachment($attachmentId)
    {
        $attachment = AssignmentAttachment::find($attachmentId);

        if ($attachment) {
            // Delete file from storage
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }

            $attachment->delete();
            Toaster::success('Attachment removed successfully!');

            // Refresh the editing assignment to show updated attachments
            if ($this->editingAssignment) {
                $this->editingAssignment = $this->editingAssignment->fresh(['attachments', 'submissions']);
            }
        }
    }

    public function softDeleteAssignment()
    {
        if ($this->deletingAssignment) {
            $this->assignmentService()->softDeleteAssignment($this->deletingAssignment);
            $this->closeAssignmentModal();
            Toaster::success('Assignment soft deleted successfully!');
        }
    }

    public function restoreAssignment()
    {
        if ($this->restoringAssignment) {
            $this->assignmentService()->restoreAssignment($this->restoringAssignment);
            $this->closeAssignmentModal();
            Toaster::success('Assignment restored successfully!');
        }
    }

    protected function assignmentService(): AssignmentService
    {
        return new AssignmentService();
    }

    //  LESSON METHODS

    public function openLessonCreateModal($sectionId)
    {
        $this->selectedSectionId = $sectionId;
        $this->resetLessonCreateForm();
        $this->showLessonCreateModal = true;
    }

    public function openLessonEditModal($id)
    {
        $this->editingLesson = $this->lessonService()->findById($id);

        if (! $this->editingLesson) {
            Toaster::error('Lesson not found.');
            return;
        }

        $this->lessonEditTitle = $this->editingLesson->title;
        $this->lessonEditType = $this->editingLesson->type;
        $this->lessonEditContent = $this->editingLesson->content;
        $this->lessonEditDuration = $this->editingLesson->duration_seconds;
        $this->lessonEditOrder = $this->editingLesson->order;
        $this->lessonEditVideoUrl = $this->editingLesson->video_url ?? '';
        $this->showLessonEditModal = true;
    }

    public function openLessonDeleteModal($id)
    {
        $this->deletingLesson = $this->lessonService()->findById($id);
        $this->showLessonDeleteModal = true;
    }

    public function openLessonRestoreModal($id)
    {
        $this->restoringLesson = $this->lessonService()->findWithTrashed($id);
        $this->showLessonRestoreModal = true;
    }

    public function closeLessonModal()
    {
        $this->showLessonCreateModal = false;
        $this->showLessonEditModal = false;
        $this->showLessonDeleteModal = false;
        $this->showLessonRestoreModal = false;
        $this->resetLessonFormFields();
    }

    public function resetLessonCreateForm()
    {
        $this->lessonCreateTitle = '';
        $this->lessonCreateType = 'video';
        $this->lessonCreateContent = '';
        $this->lessonCreateDuration = 0;
        $this->lessonCreateOrder = 0;
        $this->lessonCreateVideoUrl = '';
    }

    public function resetLessonFormFields()
    {
        $this->editingLesson = null;
        $this->deletingLesson = null;
        $this->restoringLesson = null;
        $this->lessonEditTitle = '';
        $this->lessonEditType = '';
        $this->lessonEditContent = '';
        $this->lessonEditDuration = 0;
        $this->lessonEditOrder = 0;
        $this->lessonEditVideoUrl = '';
    }

    public function storeLesson()
    {
        $this->validate($this->lessonCreateRules());

        try {
            $this->lessonService()->createLesson($this->selectedSectionId, [
                'title' => $this->lessonCreateTitle,
                'type' => $this->lessonCreateType,
                'content' => $this->lessonCreateContent,
                'duration_seconds' => $this->lessonCreateDuration,
                'order' => $this->lessonCreateOrder,
                'video_url' => $this->lessonCreateVideoUrl ?: null,
            ], $this->courseVideo);
        } catch (\Throwable $exception) {
            Toaster::error($exception->getMessage());
            $this->closeLessonModal();
            return;
        }

        $this->courseVideo = null;
        $this->closeLessonModal();
        Toaster::success('Lesson created successfully!');
    }

    public function updateLesson()
    {
        $this->validate($this->lessonUpdateRules());

        if (! $this->editingLesson) {
            Toaster::error('Lesson not found.');
            return;
        }

        $this->lessonService()->updateLesson($this->editingLesson, [
            'title' => $this->lessonEditTitle,
            'type' => $this->lessonEditType,
            'content' => $this->lessonEditContent,
            'duration_seconds' => $this->lessonEditDuration,
            'order' => $this->lessonEditOrder,
            'video_url' => $this->lessonEditVideoUrl ?: null,
        ]);

        $this->closeLessonModal();
        Toaster::success('Lesson updated successfully!');
    }

    public function softDeleteLesson()
    {
        if ($this->deletingLesson) {
            $this->lessonService()->softDeleteLesson($this->deletingLesson);
            $this->closeLessonModal();
            Toaster::success('Lesson soft deleted successfully!');
        }
    }

    public function restoreLesson()
    {
        if ($this->restoringLesson) {
            $this->lessonService()->restoreLesson($this->restoringLesson);
            $this->closeLessonModal();
            Toaster::success('Lesson restored successfully!');
        }
    }

    protected function lessonService(): LessonService
    {
        return new LessonService();
    }

    //  QUIZ METHODS

    public function openQuizCreateModal($sectionId)
    {
        $this->selectedSectionId = $sectionId;
        $this->resetQuizCreateForm();
        $this->showQuizCreateModal = true;
    }

    public function openQuizEditModal($id)
    {
        $this->editingQuiz = $this->quizService()->findQuizWithRelations($id);

        if (! $this->editingQuiz) {
            Toaster::error('Quiz not found.');
            return;
        }

        // Ensure the quiz belongs to the currently authenticated instructor
        $course = optional($this->editingQuiz->section)->course;
        if (! $course || $course->instructor_id !== auth()->id()) {
            Toaster::error('Unauthorized.');
            $this->editingQuiz = null;
            return;
        }

        $this->quizEditTitle = $this->editingQuiz->title;
        $this->quizEditPassPercentage = $this->editingQuiz->pass_percentage;
        $this->quizEditCanReattempt = $this->editingQuiz->can_reattempt ?? false;
        $this->quizEditMaxAttempts = $this->editingQuiz->max_attempts ?? 1;
        $this->showQuizEditModal = true;
    }

    public function openQuizDeleteModal($id)
    {
        $this->deletingQuiz = $this->quizService()->findById($id);
        $this->showQuizDeleteModal = true;
    }

    public function closeQuizModal()
    {
        $this->showQuizCreateModal = false;
        $this->showQuizEditModal = false;
        $this->showQuizDeleteModal = false;
        $this->resetQuizFormFields();
    }

    public function resetQuizCreateForm()
    {
        $this->quizCreateTitle = '';
        $this->quizCreatePassPercentage = 70;
        $this->quizCreateCanReattempt = false;
        $this->quizCreateMaxAttempts = 1;
    }

    public function resetQuizFormFields()
    {
        $this->editingQuiz = null;
        $this->deletingQuiz = null;
        $this->quizEditTitle = '';
        $this->quizEditPassPercentage = 70;
        $this->quizEditCanReattempt = false;
        $this->quizEditMaxAttempts = 1;
    }

    protected function courseCreateRules(): array
    {
        return [
            'createTitle' => 'required|string|max:255',
            'createSlug' => 'required|string|max:255|alpha_dash|unique:courses,slug',
            'createDescription' => 'nullable|string|max:5000',
            'createPrice' => 'required|numeric|min:0|max:999999.99',
            'createStatus' => 'required|in:draft,published,archived',
            'createInstructorId' => 'required|exists:users,id',
        ];
    }

    protected function courseUpdateRules(): array
    {
        return [
            'editTitle' => 'required|string|max:255',
            'editSlug' => 'required|string|max:255|alpha_dash|unique:courses,slug,' . optional($this->editingCourse)->id,
            'editDescription' => 'nullable|string|max:5000',
            'editPrice' => 'required|numeric|min:0|max:999999.99',
            'editStatus' => 'required|in:draft,published,archived',
            'editInstructorId' => 'required|exists:users,id',
        ];
    }

    protected function sectionCreateRules(): array
    {
        return [
            'sectionCreateTitle' => 'required|string|max:255',
            'sectionCreateOrder' => 'required|integer|min:0',
        ];
    }

    protected function sectionUpdateRules(): array
    {
        return [
            'sectionEditTitle' => 'required|string|max:255',
            'sectionEditOrder' => 'required|integer|min:0',
        ];
    }

    protected function lessonCreateRules(): array
    {
        return [
            'lessonCreateTitle' => 'required|string|max:255',
            'lessonCreateType' => 'required|in:video,text',
            'lessonCreateContent' => 'nullable|string',
            'lessonCreateOrder' => 'required|integer|min:0',
            'courseVideo' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:102400',
        ];
    }

    protected function lessonUpdateRules(): array
    {
        return [
            'lessonEditTitle' => 'required|string|max:255',
            'lessonEditType' => 'required|in:video,text,quiz',
            'lessonEditContent' => 'nullable|string',
            'lessonEditOrder' => 'required|integer|min:0',
        ];
    }

    protected function assignmentCreateRules(): array
    {
        return [
            'assignmentCreateTitle' => 'required|string|max:255',
            'assignmentCreateDescription' => 'nullable|string',
            'assignmentCreateInstructions' => 'nullable|string',
            'assignmentCreateDueDate' => 'nullable|date',
            'assignmentCreateMaxScore' => 'required|integer|min:0',
            'assignmentCreateAllowLate' => 'required|in:0,1',
            'assignmentCreateStatus' => 'required|in:draft,published,archived',
            'assignmentCreateOrder' => 'required|integer|min:0',
            'assignmentCreateAttachments' => 'nullable|file|max:10240', // 10MB max
        ];
    }

    protected function assignmentUpdateRules(): array
    {
        return [
            'assignmentEditTitle' => 'required|string|max:255',
            'assignmentEditDescription' => 'nullable|string',
            'assignmentEditInstructions' => 'nullable|string',
            'assignmentEditDueDate' => 'nullable|date',
            'assignmentEditMaxScore' => 'required|integer|min:0',
            'assignmentEditAllowLate' => 'required|in:0,1',
            'assignmentEditStatus' => 'required|in:draft,published,archived',
            'assignmentEditOrder' => 'required|integer|min:0',
            'assignmentEditAttachments' => 'nullable|file|max:10240', // 10MB max
        ];
    }

    protected function quizCreateRules(): array
    {
        return [
            'quizCreateTitle' => 'required|string|max:255',
            'quizCreatePassPercentage' => 'required|integer|min:1|max:100',
            'quizCreateMaxAttempts' => 'nullable|integer|min:1|max:100',
        ];
    }

    protected function quizUpdateRules(): array
    {
        return [
            'quizEditTitle' => 'required|string|max:255',
            'quizEditPassPercentage' => 'required|integer|min:1|max:100',
            'quizEditMaxAttempts' => 'nullable|integer|min:1|max:100',
        ];
    }

    protected function quizService(): QuizService
    {
        return new QuizService();
    }

    public function storeQuiz()
    {
        $this->validate($this->quizCreateRules());

        try {
            $this->quizService()->createQuizForSection($this->selectedSectionId, [
                'title' => $this->quizCreateTitle,
                'pass_percentage' => $this->quizCreatePassPercentage,
                'can_reattempt' => $this->quizCreateCanReattempt,
                'max_attempts' => $this->quizCreateCanReattempt ? $this->quizCreateMaxAttempts : 1,
            ]);
        } catch (\Throwable $exception) {
            Toaster::error($exception->getMessage());
            $this->closeQuizModal();
            return;
        }

        $this->closeQuizModal();
        Toaster::success('Quiz created successfully!');
    }

    public function updateQuiz()
    {
        $this->validate($this->quizUpdateRules());

        if (! $this->editingQuiz) {
            Toaster::error('Quiz not found.');
            return;
        }

        $this->quizService()->updateQuiz($this->editingQuiz, [
            'title' => $this->quizEditTitle,
            'pass_percentage' => $this->quizEditPassPercentage,
            'can_reattempt' => $this->quizEditCanReattempt,
            'max_attempts' => $this->quizEditCanReattempt ? $this->quizEditMaxAttempts : 1,
        ]);

        $this->closeQuizModal();
        Toaster::success('Quiz updated successfully!');
    }

    public function deleteQuiz()
    {
        if ($this->deletingQuiz) {
            $this->quizService()->deleteQuiz($this->deletingQuiz);
            $this->closeQuizModal();
            Toaster::success('Quiz deleted successfully!');
        }
    }

    public function render()
    {
        $courses = Course::select('id', 'title', 'slug', 'price', 'status', 'instructor_id', 'created_at')
            ->with([
                'instructor',
                'sections' => function ($query) {
                    $query->with([
                        'lessons' => function ($q) {
                            $q->withTrashed();
                        },
                        'quiz',
                        'assignments'
                    ])->withTrashed();
                }
            ])
            ->where('instructor_id', auth()->user()->id)
            ->paginate(10);

        $deletedCourses = Course::onlyTrashed()
            ->where('instructor_id', auth()->user()->id)
            ->get();

        $instructors = User::role('instructor')->get();

        return view('livewire.instructor.courses', [
            'courses' => $courses,
            'deletedCourses' => $deletedCourses,
            'instructors' => $instructors,
        ]);
    }
}
