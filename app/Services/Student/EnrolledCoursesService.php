<?php

namespace App\Services\Student;

use App\Models\Tenant\Enrollment;
use App\Models\Tenant\LessonProgress;

class EnrolledCoursesService
{
    public function getEnrolledCourses(int $userId)
    {
        return Enrollment::where('user_id', $userId)
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
            ->filter(fn($enrollment) => $enrollment->course && $enrollment->course->status === 'published');
    }

    public function getCourseProgress($enrollment, int $userId): array
    {
        $course = $enrollment->course;
        $totalLessons = 0;
        $completedLessons = 0;
        $currentLesson = null;

        foreach ($course->sections as $section) {
            foreach ($section->lessons as $lesson) {
                $totalLessons++;

                $progress = LessonProgress::where('user_id', $userId)
                    ->where('lesson_id', $lesson->id)
                    ->first();

                if ($progress && $progress->is_completed) {
                    $completedLessons++;
                }

                if (! $currentLesson && (! $progress || ! $progress->is_completed)) {
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
}
