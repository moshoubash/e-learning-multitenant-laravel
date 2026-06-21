<?php

namespace App\Services\Student;

use App\Models\Tenant\Course;
use App\Models\Tenant\Enrollment;
use App\Models\Tenant\User;
use App\Notifications\EnrollmentConfirmed;
use App\Notifications\NewEnrollment;

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
        $enrollment = Enrollment::create([
            'course_id' => $courseId,
            'user_id' => $userId,
            'status' => Enrollment::STATUS_ACTIVE,
        ]);

        $course = Course::with('instructor')->find($courseId);
        if ($course) {
            $student = User::find($userId);
            if ($course->instructor) {
                $course->instructor->notify(new NewEnrollment($student, $course));
            }
            if ($student) {
                $student->notify(new EnrollmentConfirmed($course));
            }
        }

        return $enrollment;
    }
}
