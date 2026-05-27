<?php

namespace App\Services\Instructor;

use App\Models\Tenant\Course;

class CourseService
{
    public function findById(int $id): ?Course
    {
        return Course::find($id);
    }

    public function findWithTrashed(int $id): ?Course
    {
        return Course::withTrashed()->find($id);
    }

    public function createCourse(array $data): Course
    {
        return Course::create($data);
    }

    public function updateCourse(Course $course, array $data): Course
    {
        $course->update($data);

        return $course;
    }

    public function softDeleteCourse(Course $course): void
    {
        $course->delete();
    }

    public function restoreCourse(Course $course): void
    {
        $course->restore();
    }
}
