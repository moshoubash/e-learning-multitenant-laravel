<?php

namespace App\Livewire\Student;

use App\Models\Tenant\Assignment;
use App\Models\Tenant\AssignmentSubmission;
use App\Models\Tenant\Course;
use App\Models\Tenant\Enrollment;
use App\Models\Tenant\Lesson;
use App\Models\Tenant\LessonProgress;
use App\Notifications\AssignmentSubmitted;
use App\Services\Student\CourseContentService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Storage;
use Yaza\LaravelGoogleDriveStorage\Gdrive;

#[Layout('layouts.student')]
class CourseContent extends Component
{
    use WithFileUploads;

    public $courseId;
    public $selectedLesson = null;
    public $selectedAssignment = null;
    public $expandedSections = [];
    public $progressPercent = 0;
    public $course = null;

    // Submission form fields
    public $submissionContent = '';
    public $submissionFiles = null;
    public $showSubmissionForm = false;

    public function mount(Course $course)
    {
        $this->courseId = $course->id;
        $this->course = $course;

        if (! $this->courseContentService()->ensureEnrolled($this->courseId, auth()->id())) {
            return redirect()->route('tenant.student.courses');
        }

        $course = $this->courseContentService()->getCourse($this->courseId);

        if (!$this->selectedLesson && $course) {
            $this->selectedLesson = $this->courseContentService()->getFirstIncompleteLesson($course, auth()->id())
                ?? $course->sections->first()?->lessons->first();
        }

        $this->progressPercent = $this->courseContentService()->calculateProgress($this->courseId, auth()->id());
        $this->syncProgressFromEnrollment();
    }

    protected function syncProgressFromEnrollment(): void
    {
        $enrollment = \App\Models\Tenant\Enrollment::where('course_id', $this->courseId)
            ->where('user_id', auth()->id())
            ->first();

        if ($enrollment) {
            $this->progressPercent = (int) $enrollment->progress_percent;
        }
    }

    public function getCourse()
    {
        return $this->courseContentService()->getCourse($this->courseId);
    }

    public function selectLesson($lessonId)
    {
        $this->selectedLesson = Lesson::with([
            'section' => function ($q) {
                $q->with('course');
            }
        ])->find($lessonId);
        $this->selectedAssignment = null;
        $this->showSubmissionForm = false;

        // Expand the section containing this lesson
        if ($this->selectedLesson && $this->selectedLesson->section) {
            if (!in_array($this->selectedLesson->section->id, $this->expandedSections)) {
                $this->expandedSections[] = $this->selectedLesson->section->id;
            }
        }
    }

    public function selectAssignment($assignmentId)
    {
        $this->selectedAssignment = Assignment::with([
            'section.course',
            'attachments',
            'submissions' => function ($query) {
                $query->where('student_id', auth()->id())->with('grades');
            }
        ])->find($assignmentId);
        $this->selectedLesson = null;
        $this->showSubmissionForm = false;

        if ($this->selectedAssignment && $this->selectedAssignment->section) {
            if (!in_array($this->selectedAssignment->section->id, $this->expandedSections)) {
                $this->expandedSections[] = $this->selectedAssignment->section->id;
            }
        }
    }

    public function toggleSection($sectionId)
    {
        if (in_array($sectionId, $this->expandedSections)) {
            $this->expandedSections = array_filter($this->expandedSections, fn($id) => $id !== $sectionId);
        } else {
            $this->expandedSections[] = $sectionId;
        }
    }

    public function isSectionExpanded($sectionId)
    {
        return in_array($sectionId, $this->expandedSections);
    }

    public function goBack()
    {
        // This method is used by child components to navigate back
    }

    public function markLessonComplete()
    {
        if (!$this->selectedLesson) {
            return;
        }

        $this->courseContentService()->markLessonComplete($this->selectedLesson->id, auth()->id());

        $this->progressPercent = $this->courseContentService()->calculateProgress($this->courseId, auth()->id());

        if ($this->progressPercent === 100) {
            Enrollment::where('course_id', $this->courseId)
                ->where('user_id', auth()->id())
                ->update([
                    'progress_percent' => 100,
                    'completed_at' => now(),
                    'status' => Enrollment::STATUS_COMPLETED,
                ]);
        }

        Toaster::success('Lesson marked as complete!');
    }

    public function refreshProgress(): void
    {
        $this->syncProgressFromEnrollment();
    }

    public function isLessonCompleted($lessonId)
    {
        return $this->courseContentService()->isLessonCompleted($lessonId, auth()->id());
    }

    // Submission methods
    public function toggleSubmissionForm()
    {
        $this->showSubmissionForm = ! $this->showSubmissionForm;
        $this->submissionContent = '';
        $this->submissionFiles = null;
    }

    public function submitAssignment()
    {
        $this->validate([
            'submissionContent' => 'nullable|string',
            'submissionFiles' => 'nullable|file|max:10240', // 10MB max
        ]);

        if (empty($this->submissionContent) && empty($this->submissionFiles)) {
            Toaster::error('Please provide content or upload files.');
            return;
        }

        // Check if late submission and if allowed
        $isLate = false;
        if ($this->selectedAssignment->due_date && now()->gt($this->selectedAssignment->due_date)) {
            $isLate = true;
            if (!$this->selectedAssignment->allow_late) {
                Toaster::error('Late submissions are not allowed for this assignment.');
                return;
            }
        }

        // Create submission
        $submission = AssignmentSubmission::create([
            'assignment_id' => $this->selectedAssignment->id,
            'student_id' => auth()->id(),
            'content' => $this->submissionContent,
            'submitted_at' => now(),
            'status' => 'submitted',
            'attempt_number' => $this->selectedAssignment->submissions->count() + 1,
        ]);

        $instructor = $this->selectedAssignment->section?->course?->instructor
            ?? $this->selectedAssignment->createdBy;
        if ($instructor) {
            $instructor->notify(new AssignmentSubmitted(auth()->user(), $this->selectedAssignment));
        }

        // Store file
        if ($this->submissionFiles) {
            $tenantId = tenant('id') ?? 'default';
            $baseUrl = 'https://d1w6oovjx4x1vx.cloudfront.net';
            $path = $this->submissionFiles->storeAs("submissions/{$tenantId}", $this->submissionFiles->getClientOriginalName(), 's3');
            $submission->update([
                'file_path' => $baseUrl . '/' . $path,
            ]);
        }

        $this->submissionFiles = null;
        $this->submissionContent = '';
        $this->showSubmissionForm = false;

        // Refresh the selected assignment
        $this->selectAssignment($this->selectedAssignment->id);

        Toaster::success('Assignment submitted successfully!');
    }

    public function getUserSubmission()
    {
        if (!$this->selectedAssignment) {
            return null;
        }

        return $this->selectedAssignment->submissions
            ->where('student_id', auth()->id())
            ->sortByDesc('submitted_at')
            ->first();
    }

    public function isAssignmentPastDue()
    {
        if (!$this->selectedAssignment || !$this->selectedAssignment->due_date) {
            return false;
        }

        return now()->gt($this->selectedAssignment->due_date);
    }

    public function canSubmitLate()
    {
        return $this->selectedAssignment && $this->selectedAssignment->allow_late;
    }

    protected function courseContentService(): CourseContentService
    {
        return new CourseContentService();
    }

    public function isCourseCompleted(): bool
    {
        return Enrollment::where('course_id', $this->courseId)
            ->where('user_id', auth()->id())
            ->where('status', Enrollment::STATUS_COMPLETED)
            ->exists();
    }

    public function render()
    {
        $course = $this->getCourse();

        return view('livewire.student.course-content', [
            'course' => $course,
        ]);
    }
}
