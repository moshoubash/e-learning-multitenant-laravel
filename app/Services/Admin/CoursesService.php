<?php

namespace App\Services\Admin;

use App\Models\Tenant\Course;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CoursesService
{
    public function findById(int $id): ?Course
    {
        return Course::find($id);
    }

    public function findWithTrashed(int $id): ?Course
    {
        return Course::withTrashed()->find($id);
    }

    public function getPaginatedCourses(int $perPage = 10)
    {
        return Course::with('instructor', 'department')->paginate($perPage);
    }

    public function getDeletedCourses()
    {
        return Course::onlyTrashed()->with('instructor')->get();
    }

    public function createCourse(array $data): Course
    {
        $slug = Str::slug($data['title']);
        $originalSlug = $slug;
        $count = 1;

        while (Course::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $data['slug'] = $slug;

        return Course::create($data);
    }

    public function updateCourse(Course $course, array $data): Course
    {
        $slug = Str::slug($data['title']);
        $originalSlug = $slug;
        $count = 1;

        while (Course::where('slug', $slug)->where('id', '!=', $course->id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $data['slug'] = $slug;

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
