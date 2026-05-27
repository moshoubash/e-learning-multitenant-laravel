<?php

namespace App\Actions\Quiz;

use App\Models\Tenant\QuizAttempt;
use Illuminate\Support\Collection;

final class QuizAttempts
{
    public function getAttemptsForQuiz(int $quizId): Collection
    {
        return QuizAttempt::with('user')
            ->where('quiz_id', $quizId)
            ->orderBy('submitted_at', 'desc')
            ->get();
    }
}
