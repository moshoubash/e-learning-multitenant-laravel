<?php

namespace App\Livewire\Student;

use App\Models\Tenant\Enrollment;
use App\Models\Tenant\LessonProgress;
use Livewire\Component;

class EnrolledCourses extends Component
{
    public function getEnrolledCourses()
    {
        return Enrollment::where('user_id', auth()->id())
            ->with([
                'course' => function ($query) {
                    $query->with([
                        'instructor',
                        'sections' => function ($q) {
                            $q->with('lessons')->orderBy('order');
                        }
                    ]);
                }
            ])
            ->get()
            ->filter(fn($e) => $e->course && $e->course->status === 'published');
    }

    public function getCourseProgress($enrollment)
    {
        $course = $enrollment->course;
        $totalLessons = 0;
        $completedLessons = 0;
        $currentLesson = null;

        foreach ($course->sections as $section) {
            foreach ($section->lessons as $lesson) {
                $totalLessons++;

                $progress = LessonProgress::where('user_id', auth()->id())
                    ->where('lesson_id', $lesson->id)
                    ->first();

                if ($progress && $progress->is_completed) {
                    $completedLessons++;
                }

                // Find the first incomplete lesson as current checkpoint
                if (!$currentLesson && (!$progress || !$progress->is_completed)) {
                    $currentLesson = $lesson;
                }
            }
        }

        $progressPercent = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

        return [
            'total_lessons' => $totalLessons,
            'completed_lessons' => $completedLessons,
            'progress_percent' => $progressPercent,
            'current_lesson' => $currentLesson,
        ];
    }

    public function render()
    {
        $enrollments = $this->getEnrolledCourses();

        return view('livewire.student.enrolled-courses', [
            'enrollments' => $enrollments,
        ]);
    }
}