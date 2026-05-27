<?php

namespace App\Livewire\Student;

use App\Models\Tenant\Course;
use App\Models\Tenant\Enrollment;
use App\Models\Tenant\Lesson;
use App\Models\Tenant\LessonProgress;
use App\Services\Student\CourseContentService;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class CourseContent extends Component
{
    public $courseId;
    public $selectedLesson = null;
    public $expandedSections = [];
    public $progressPercent = 0;
    public $course = null;

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

        // Expand the section containing this lesson
        if ($this->selectedLesson && $this->selectedLesson->section) {
            if (!in_array($this->selectedLesson->section->id, $this->expandedSections)) {
                $this->expandedSections[] = $this->selectedLesson->section->id;
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
        if ($this->selectedLesson) {
            $this->courseContentService()->markLessonComplete($this->selectedLesson->id, auth()->id());
            $this->progressPercent = $this->courseContentService()->calculateProgress($this->courseId, auth()->id());
            Toaster::success('Lesson marked as complete!');
        }
    }

    public function isLessonCompleted($lessonId)
    {
        return $this->courseContentService()->isLessonCompleted($lessonId, auth()->id());
    }

    protected function courseContentService(): CourseContentService
    {
        return new CourseContentService();
    }

    public function render()
    {
        $course = $this->getCourse();

        return view('livewire.student.course-content', [
            'course' => $course,
        ]);
    }
}
