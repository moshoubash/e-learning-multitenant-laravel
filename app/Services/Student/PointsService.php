<?php

namespace App\Services\Student;

use App\Models\Tenant\PointsTransaction;
use App\Models\Tenant\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PointsService
{
    const LESSON_POINTS = 10;
    const QUIZ_PASS_POINTS = 50;
    const QUIZ_HIGH_SCORE_BONUS = 10;
    const QUIZ_HIGH_SCORE_THRESHOLD = 90;
    const COURSE_COMPLETE_POINTS = 100;

    public function awardLessonComplete(int $userId, int $lessonId): void
    {
        $alreadyAwarded = PointsTransaction::where('user_id', $userId)
            ->where('source_type', 'lesson')
            ->where('source_id', $lessonId)
            ->exists();

        if ($alreadyAwarded) {
            return;
        }

        DB::transaction(function () use ($userId, $lessonId) {
            PointsTransaction::create([
                'user_id' => $userId,
                'points' => self::LESSON_POINTS,
                'source_type' => 'lesson',
                'source_id' => $lessonId,
                'description' => 'Completed a lesson',
            ]);

            User::where('id', $userId)->increment('total_points', self::LESSON_POINTS);
        });
    }

    public function awardQuizPass(int $userId, int $quizId, int $score): void
    {
        $alreadyAwarded = PointsTransaction::where('user_id', $userId)
            ->where('source_type', 'quiz')
            ->where('source_id', $quizId)
            ->exists();

        if ($alreadyAwarded) {
            return;
        }

        $points = self::QUIZ_PASS_POINTS;
        $description = 'Passed a quiz';

        if ($score >= self::QUIZ_HIGH_SCORE_THRESHOLD) {
            $points += self::QUIZ_HIGH_SCORE_BONUS;
            $description = 'Passed a quiz with high score';
        }

        DB::transaction(function () use ($userId, $quizId, $points, $description) {
            PointsTransaction::create([
                'user_id' => $userId,
                'points' => $points,
                'source_type' => 'quiz',
                'source_id' => $quizId,
                'description' => $description,
            ]);

            User::where('id', $userId)->increment('total_points', $points);
        });
    }

    public function awardCourseComplete(int $userId, int $courseId): void
    {
        $alreadyAwarded = PointsTransaction::where('user_id', $userId)
            ->where('source_type', 'course')
            ->where('source_id', $courseId)
            ->exists();

        if ($alreadyAwarded) {
            return;
        }

        DB::transaction(function () use ($userId, $courseId) {
            PointsTransaction::create([
                'user_id' => $userId,
                'points' => self::COURSE_COMPLETE_POINTS,
                'source_type' => 'course',
                'source_id' => $courseId,
                'description' => 'Completed a course',
            ]);

            User::where('id', $userId)->increment('total_points', self::COURSE_COMPLETE_POINTS);
        });
    }

    public function getLeaderboard(int $limit = 20): Collection
    {
        return User::whereHas('roles', function ($q) {
                $q->where('name', 'student');
            })
            ->where('total_points', '>', 0)
            ->orderByDesc('total_points')
            ->take($limit)
            ->get(['id', 'name', 'avatar', 'total_points']);
    }

    public function getUserRank(int $userId): ?int
    {
        $userPoints = User::where('id', $userId)->value('total_points');

        if (! $userPoints) {
            return null;
        }

        return User::whereHas('roles', function ($q) {
                $q->where('name', 'student');
            })
            ->where('total_points', '>', $userPoints)
            ->count() + 1;
    }

    public function getUserTotalPoints(int $userId): int
    {
        return (int) User::where('id', $userId)->value('total_points');
    }
}
