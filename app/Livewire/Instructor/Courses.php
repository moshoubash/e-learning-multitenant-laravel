<?php

namespace App\Livewire\Instructor;

use App\Models\Tenant\Course;
use App\Models\Tenant\Section;
use App\Models\Tenant\Lesson;
use App\Models\Tenant\Quiz;
use App\Models\Tenant\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;
use League\Flysystem\AwsS3V3\PortableVisibilityConverter;

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
    public $createThumbnail = null;

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

    // Lesson edit form fields
    public $lessonEditTitle = '';
    public $lessonEditType = '';
    public $lessonEditContent = '';
    public $lessonEditDuration = 0;
    public $lessonEditOrder = 0;
    public $lessonEditVideoUrl = '';

    // Quiz create form fields
    public $quizCreateTitle = '';
    public $quizCreatePassPercentage = 70;

    // Quiz edit form fields
    public $quizEditTitle = '';
    public $quizEditPassPercentage = 70;

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
        $this->resetCreateForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($id)
    {
        $this->editingCourse = Course::find($id);
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
        $this->deletingCourse = Course::find($id);
        $this->showDeleteModal = true;
    }

    public function openRestoreModal($id)
    {
        $this->restoringCourse = Course::withTrashed()->find($id);
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
        $this->createThumbnail = null;
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
        $this->validate([
            'createTitle' => 'required|string|max:255',
            'createSlug' => 'required|string|max:255|unique:courses,slug',
            'createDescription' => 'nullable|string',
            'createPrice' => 'required|numeric|min:0',
            'createStatus' => 'required|in:draft,published,archived',
            'createInstructorId' => 'required|exists:users,id',
            'createAvatar' => 'nullable|image|max:2048',
        ]);

        // Get tenant ID from current tenant context
        $tenantId = tenant('id') ?? 'default';

        $thumbnailUrl = null;

        if ($this->createThumbnail) {
            $thumbnailPath = $this->createThumbnail->storeAs(
                "courses/$tenantId/thumbnails",
                $this->createSlug . '-' . time() . '.' . $this->createThumbnail->getClientOriginalExtension(),
                's3'
            );
            $thumbnailUrl = Storage::disk('s3')->url($thumbnailPath);
        }

        Course::create([
            'title' => $this->createTitle,
            'slug' => $this->createSlug,
            'description' => $this->createDescription,
            'thumbnail' => $thumbnailUrl,
            'price' => $this->createPrice,
            'status' => $this->createStatus,
            'instructor_id' => $this->createInstructorId,
        ]);

        $this->createThumbnail = null;
        $this->closeModal();
        Toaster::success('Course created successfully!');
    }

    public function update()
    {
        $this->validate([
            'editTitle' => 'required|string|max:255',
            'editSlug' => 'required|string|max:255|unique:courses,slug,' . $this->editingCourse->id,
            'editDescription' => 'nullable|string',
            'editPrice' => 'required|numeric|min:0',
            'editStatus' => 'required|in:draft,published,archived',
            'editInstructorId' => 'required|exists:users,id',
        ]);

        $this->editingCourse->title = $this->editTitle;
        $this->editingCourse->slug = $this->editSlug;
        $this->editingCourse->description = $this->editDescription;
        $this->editingCourse->price = $this->editPrice;
        $this->editingCourse->status = $this->editStatus;
        $this->editingCourse->instructor_id = $this->editInstructorId;

        $this->editingCourse->save();

        $this->closeModal();
        Toaster::success('Course updated successfully!');
    }

    public function softDelete()
    {
        if ($this->deletingCourse) {
            $this->deletingCourse->delete();
            $this->closeModal();
            Toaster::success('Course soft deleted successfully!');
        }
    }

    public function restore()
    {
        if ($this->restoringCourse) {
            $this->restoringCourse->restore();
            $this->closeModal();
            Toaster::success('Course restored successfully!');
        }
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
        $this->editingSection = Section::find($id);
        $this->sectionEditTitle = $this->editingSection->title;
        $this->sectionEditOrder = $this->editingSection->order;
        $this->showSectionEditModal = true;
    }

    public function openSectionDeleteModal($id)
    {
        $this->deletingSection = Section::find($id);
        $this->showSectionDeleteModal = true;
    }

    public function openSectionRestoreModal($id)
    {
        $this->restoringSection = Section::withTrashed()->find($id);
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
        $this->validate([
            'sectionCreateTitle' => 'required|string|max:255',
            'sectionCreateOrder' => 'required|integer|min:0',
        ]);

        Section::create([
            'course_id' => $this->selectedCourseId,
            'title' => $this->sectionCreateTitle,
            'order' => $this->sectionCreateOrder,
        ]);

        $this->closeSectionModal();
        Toaster::success('Section created successfully!');
    }

    public function updateSection()
    {
        $this->validate([
            'sectionEditTitle' => 'required|string|max:255',
            'sectionEditOrder' => 'required|integer|min:0',
        ]);

        $this->editingSection->title = $this->sectionEditTitle;
        $this->editingSection->order = $this->sectionEditOrder;

        $this->editingSection->save();

        $this->closeSectionModal();
        Toaster::success('Section updated successfully!');
    }

    public function softDeleteSection()
    {
        if ($this->deletingSection) {
            // Also delete the quiz if exists
            if ($this->deletingSection->quiz) {
                $this->deletingSection->quiz->questions()->each(function ($question) {
                    $question->options()->delete();
                });
                $this->deletingSection->quiz->questions()->delete();
                $this->deletingSection->quiz->delete();
            }
            $this->deletingSection->lessons()->delete();
            $this->deletingSection->delete();
            $this->closeSectionModal();
            Toaster::success('Section soft deleted successfully!');
        }
    }

    public function restoreSection()
    {
        if ($this->restoringSection) {
            $this->restoringSection->restore();
            $this->closeSectionModal();
            Toaster::success('Section restored successfully!');
        }
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
        $this->editingLesson = Lesson::find($id);
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
        $this->deletingLesson = Lesson::find($id);
        $this->showLessonDeleteModal = true;
    }

    public function openLessonRestoreModal($id)
    {
        $this->restoringLesson = Lesson::withTrashed()->find($id);
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
        $this->validate([
            'lessonCreateTitle' => 'required|string|max:255',
            'lessonCreateType' => 'required|in:video,text',
            'lessonCreateContent' => 'nullable|string',
            'lessonCreateDuration' => 'nullable|integer|min:0',
            'lessonCreateOrder' => 'required|integer|min:0',
            'courseVideo' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:102400',
        ]);

        // Get tenant ID from current tenant context
        $tenantId = tenant('id') ?? 'default';

        $videoUrl = null;

        if ($this->courseVideo) {
            $this->lessonCreateVideoUrl = $this->courseVideo->storeAs("courses/$tenantId", $this->courseVideo->getClientOriginalName() . rand() . time(), 's3');
            $videoUrl = Storage::disk('s3')->url("courses/$tenantId" . $this->courseVideo->getClientOriginalName());
        }

        Lesson::create([
            'section_id' => $this->selectedSectionId,
            'title' => $this->lessonCreateTitle,
            'type' => $this->lessonCreateType,
            'content' => $this->lessonCreateContent,
            'duration_seconds' => $this->lessonCreateDuration,
            'order' => $this->lessonCreateOrder,
            'video_url' => $videoUrl,
        ]);

        $this->courseVideo = null;
        $this->closeLessonModal();
        Toaster::success('Lesson created successfully!');
    }

    public function updateLesson()
    {
        $this->validate([
            'lessonEditTitle' => 'required|string|max:255',
            'lessonEditType' => 'required|in:video,text,quiz',
            'lessonEditContent' => 'nullable|string',
            'lessonEditDuration' => 'nullable|integer|min:0',
            'lessonEditOrder' => 'required|integer|min:0',
        ]);

        $this->editingLesson->title = $this->lessonEditTitle;
        $this->editingLesson->type = $this->lessonEditType;
        $this->editingLesson->content = $this->lessonEditContent;
        $this->editingLesson->duration_seconds = $this->lessonEditDuration;
        $this->editingLesson->order = $this->lessonEditOrder;
        $this->editingLesson->video_url = $this->lessonEditVideoUrl ?: null;

        $this->editingLesson->save();

        $this->closeLessonModal();
        Toaster::success('Lesson updated successfully!');
    }

    public function softDeleteLesson()
    {
        if ($this->deletingLesson) {
            $this->deletingLesson->delete();
            $this->closeLessonModal();
            Toaster::success('Lesson soft deleted successfully!');
        }
    }

    public function restoreLesson()
    {
        if ($this->restoringLesson) {
            $this->restoringLesson->restore();
            $this->closeLessonModal();
            Toaster::success('Lesson restored successfully!');
        }
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
        $this->editingQuiz = Quiz::with('questions.options')->find($id);
        $this->quizEditTitle = $this->editingQuiz->title;
        $this->quizEditPassPercentage = $this->editingQuiz->pass_percentage;
        $this->showQuizEditModal = true;
    }

    public function openQuizDeleteModal($id)
    {
        $this->deletingQuiz = Quiz::find($id);
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
    }

    public function resetQuizFormFields()
    {
        $this->editingQuiz = null;
        $this->deletingQuiz = null;
        $this->quizEditTitle = '';
        $this->quizEditPassPercentage = 70;
    }

    public function storeQuiz()
    {
        $this->validate([
            'quizCreateTitle' => 'required|string|max:255',
            'quizCreatePassPercentage' => 'required|integer|min:1|max:100',
        ]);

        // Check if section already has a quiz
        $section = Section::find($this->selectedSectionId);
        if ($section && $section->quiz) {
            Toaster::error('This section already has a quiz. Please edit the existing quiz instead.');
            $this->closeQuizModal();
            return;
        }

        Quiz::create([
            'section_id' => $this->selectedSectionId,
            'title' => $this->quizCreateTitle,
            'pass_percentage' => $this->quizCreatePassPercentage,
        ]);

        $this->closeQuizModal();
        Toaster::success('Quiz created successfully!');
    }

    public function updateQuiz()
    {
        $this->validate([
            'quizEditTitle' => 'required|string|max:255',
            'quizEditPassPercentage' => 'required|integer|min:1|max:100',
        ]);

        $this->editingQuiz->title = $this->quizEditTitle;
        $this->editingQuiz->pass_percentage = $this->quizEditPassPercentage;
        $this->editingQuiz->save();

        $this->closeQuizModal();
        Toaster::success('Quiz updated successfully!');
    }

    public function deleteQuiz()
    {
        if ($this->deletingQuiz) {
            $this->deletingQuiz->questions()->each(function ($question) {
                $question->options()->delete();
            });
            $this->deletingQuiz->questions()->delete();
            $this->deletingQuiz->delete();
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
                        'quiz'
                    ])->withTrashed();
                }
            ])
            ->paginate(10);

        $deletedCourses = Course::onlyTrashed()->with('instructor')->get();
        $instructors = User::role('instructor')->get();

        return view('livewire.instructor.courses', [
            'courses' => $courses,
            'deletedCourses' => $deletedCourses,
            'instructors' => $instructors,
        ]);
    }
}