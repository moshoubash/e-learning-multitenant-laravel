<?php

namespace App\Services\Student;

use App\Jobs\RecalculateCourseProgress;
use App\Models\Tenant\Course;
use App\Models\Tenant\Enrollment;
use App\Models\Tenant\Lesson;
use App\Models\Tenant\LessonProgress;
use Illuminate\Support\Facades\Cache;

class CourseContentService
{
    public function ensureEnrolled(int $courseId, int $userId): bool
    {
        return Enrollment::where('course_id', $courseId)
            ->where('user_id', $userId)
            ->exists();
    }

    public function getCourse(int $courseId): ?Course
    {
        return Cache::remember("course:content:{$courseId}", 600, function () use ($courseId) {
            return Course::with([
                'instructor',
                'sections' => function ($query) {
                    $query->with([
                        'lessons' => function ($q) {
                            $q->orderBy('order');
                        },
                        'quiz' => function ($q) {
                            $q->with('questions.options');
                        },
                        'assignments' => function ($q) {
                            $q->orderBy('order');
                        }
                    ])->orderBy('order');
                }
            ])->find($courseId);
        });
    }

    public function getFirstIncompleteLesson(Course $course, int $userId): ?Lesson
    {
        foreach ($course->sections as $section) {
            foreach ($section->lessons as $lesson) {
                if (! $this->isLessonCompleted($lesson->id, $userId)) {
                    return $lesson;
                }
            }
        }

        return null;
    }

    public function markLessonComplete(int $lessonId, int $userId): LessonProgress
    {
        $progress = LessonProgress::updateOrCreate(
            [
                'user_id' => $userId,
                'lesson_id' => $lessonId,
            ],
            [
                'is_completed' => true,
                'last_watched_at' => now(),
            ]
        );

        $lesson = Lesson::with('section')->find($lessonId);
        if ($lesson && $lesson->section) {
            $this->dispatchProgressRecalculation($lesson->section->course_id, $userId);
        }

        return $progress;
    }

    public function dispatchProgressRecalculation(int $courseId, int $userId): void
    {
        RecalculateCourseProgress::dispatch($courseId, $userId);
    }

    public function isLessonCompleted(int $lessonId, int $userId): bool
    {
        return LessonProgress::where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->where('is_completed', true)
            ->exists();
    }

    public function calculateProgress(int $courseId, int $userId): int
    {
        $totalLessons = Lesson::whereHas('section', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->count();

        if ($totalLessons === 0) {
            return 0;
        }

        $completedLessons = LessonProgress::where('user_id', $userId)
            ->whereHas('lesson.section', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->where('is_completed', true)
            ->count();

        return (int) round(($completedLessons / $totalLessons) * 100);
    }
}
