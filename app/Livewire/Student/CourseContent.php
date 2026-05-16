<?php

namespace App\Livewire\Student;

use App\Models\Tenant\Course;
use App\Models\Tenant\Enrollment;
use App\Models\Tenant\Lesson;
use App\Models\Tenant\LessonProgress;
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

        // Check if user is enrolled
        $enrollment = Enrollment::where('course_id', $this->courseId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$enrollment) {
            return redirect()->route('tenant.student.courses');
        }

        // Load course with all data
        $course = $this->getCourse();

        // Set initial lesson if not selected
        if (!$this->selectedLesson && $course) {
            $this->selectedLesson = $this->getFirstIncompleteLesson($course) ?? $course->sections->first()?->lessons->first();
        }

        // Calculate progress
        $this->calculateProgress();
    }

    public function getCourse()
    {
        return Course::with([
            'instructor',
            'sections' => function ($query) {
                $query->with([
                    'lessons' => function ($q) {
                        $q->orderBy('order');
                    },
                    'quiz' => function ($q) {
                        $q->with('questions.options');
                    }
                ])->orderBy('order');
            }
        ])->find($this->courseId);
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

    public function markLessonComplete()
    {
        if ($this->selectedLesson) {
            LessonProgress::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'lesson_id' => $this->selectedLesson->id,
                ],
                [
                    'is_completed' => true,
                    'last_watched_at' => now(),
                ]
            );

            $this->calculateProgress();
            Toaster::success('Lesson marked as complete!');
        }
    }

    public function isLessonCompleted($lessonId)
    {
        return LessonProgress::where('user_id', auth()->id())
            ->where('lesson_id', $lessonId)
            ->where('is_completed', true)
            ->exists();
    }

    private function getFirstIncompleteLesson($course)
    {
        foreach ($course->sections as $section) {
            foreach ($section->lessons as $lesson) {
                if (!$this->isLessonCompleted($lesson->id)) {
                    return $lesson;
                }
            }
        }
        return null;
    }

    private function calculateProgress()
    {
        $totalLessons = Lesson::whereHas('section', function ($query) {
            $query->where('course_id', $this->courseId);
        })->count();

        $completedLessons = LessonProgress::where('user_id', auth()->id())
            ->whereHas('lesson.section', function ($query) {
                $query->where('course_id', $this->courseId);
            })
            ->where('is_completed', true)
            ->count();

        $this->progressPercent = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

        // Persist to enrollment table
        Enrollment::where('course_id', $this->courseId)
            ->where('user_id', auth()->id())
            ->update([
                'progress_percent' => $this->progressPercent,
                'completed_at' => $this->progressPercent == 100 ? now() : null,
            ]);
    }

    public function render()
    {
        $course = $this->getCourse();

        return view('livewire.student.course-content', [
            'course' => $course,
        ]);
    }
}