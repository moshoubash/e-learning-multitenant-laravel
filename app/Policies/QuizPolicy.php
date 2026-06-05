<?php

namespace App\Policies;

use App\Models\Tenant\Quiz;
use App\Models\Tenant\User;

/**
 * Authorization for quizzes.
 *
 * - Admins: full access.
 * - Instructors: full access to quizzes of their own courses.
 * - Students: may only attempt published quizzes of courses they are enrolled in.
 */
class QuizPolicy
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

    public function view(User $user, Quiz $quiz): bool
    {
        if ($user->hasRole('instructor')
            && $quiz->course
            && (int) $quiz->course->instructor_id === (int) $user->id) {
            return true;
        }

        if ($user->hasRole('student')
            && $quiz->course
            && $quiz->course->enrollments()->where('user_id', $user->id)->exists()) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('instructor');
    }

    public function update(User $user, Quiz $quiz): bool
    {
        return $user->hasRole('instructor')
            && $quiz->course
            && (int) $quiz->course->instructor_id === (int) $user->id;
    }

    public function delete(User $user, Quiz $quiz): bool
    {
        return $this->update($user, $quiz);
    }

    public function attempt(User $user, Quiz $quiz): bool
    {
        return $user->hasRole('student')
            && $quiz->course
            && $quiz->course->enrollments()->where('user_id', $user->id)->exists();
    }
}
