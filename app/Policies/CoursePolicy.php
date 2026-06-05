<?php

namespace App\Policies;

use App\Models\Tenant\Course;
use App\Models\Tenant\User;

/**
 * Authorization for courses.
 *
 * - Admins may do anything.
 * - Instructors may view and modify courses they own.
 * - Students may only view published courses.
 */
class CoursePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['instructor', 'student']);
    }

    public function view(User $user, Course $course): bool
    {
        if ($user->hasRole('instructor') && (int) $course->instructor_id === (int) $user->id) {
            return true;
        }

        if ($user->hasRole('student') && $course->status === 'published') {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('instructor');
    }

    public function update(User $user, Course $course): bool
    {
        return $user->hasRole('instructor')
            && (int) $course->instructor_id === (int) $user->id;
    }

    public function delete(User $user, Course $course): bool
    {
        return $this->update($user, $course);
    }

    public function restore(User $user, Course $course): bool
    {
        return $this->update($user, $course);
    }

    public function forceDelete(User $user, Course $course): bool
    {
        return $user->hasRole('admin');
    }
}
