<?php

namespace App\Services\Student;

use App\Models\Tenant\Course;
use App\Models\Tenant\Enrollment;

class CoursesService
{
    public function getCourses()
    {
        return Course::with([
            'instructor',
            'sections' => function ($query) {
                $query->with([
                    'lessons',
                    'quiz' => function ($q) {
                        $q->with('questions.options');
                    }
                ])->orderBy('order');
            }
        ])
            ->where('status', 'published')
            ->orderBy('title')
            ->get();
    }

    public function getCourseById(int $courseId): ?Course
    {
        return Course::with([
            'instructor',
            'sections' => function ($query) {
                $query->with([
                    'lessons',
                    'quiz' => function ($q) {
                        $q->with('questions.options');
                    }
                ])->orderBy('order');
            }
        ])->find($courseId);
    }

    public function isEnrolled(int $courseId, int $userId): bool
    {
        return Enrollment::where('course_id', $courseId)
            ->where('user_id', $userId)
            ->exists();
    }

    public function enrollInCourse(int $courseId, int $userId)
    {
        return Enrollment::create([
            'course_id' => $courseId,
            'user_id' => $userId,
            'status' => 'active',
        ]);
    }
}
