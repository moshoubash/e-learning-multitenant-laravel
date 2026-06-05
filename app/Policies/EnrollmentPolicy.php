<?php

namespace App\Policies;

use App\Models\Tenant\Enrollment;
use App\Models\Tenant\User;

/**
 * Authorization for enrollments.
 *
 * Users may only see and modify their own enrollments. Admins always allowed.
 */
class EnrollmentPolicy
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

    public function view(User $user, Enrollment $enrollment): bool
    {
        return (int) $enrollment->user_id === (int) $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('student');
    }

    public function update(User $user, Enrollment $enrollment): bool
    {
        return (int) $enrollment->user_id === (int) $user->id;
    }

    public function delete(User $user, Enrollment $enrollment): bool
    {
        return (int) $enrollment->user_id === (int) $user->id;
    }
}
