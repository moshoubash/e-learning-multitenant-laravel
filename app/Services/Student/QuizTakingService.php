<?php

namespace App\Services\Student;

use App\Models\Tenant\Quiz;
use App\Models\Tenant\QuizAttempt;

class QuizTakingService
{
    public function loadQuiz(int $quizId): ?Quiz
    {
        return Quiz::with(['questions.options', 'section'])->find($quizId);
    }

    public function getPreviousAttempt(int $quizId, int $userId)
    {
        return QuizAttempt::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->latest('submitted_at')
            ->first();
    }

    public function calculateScore(Quiz $quiz, array $selectedAnswers): array
    {
        $correctAnswers = 0;

        foreach ($quiz->questions as $question) {
            $correctOption = $question->options->where('is_correct', true)->first();

            if ($correctOption && isset($selectedAnswers[$question->id]) && $selectedAnswers[$question->id] === $correctOption->id) {
                $correctAnswers++;
            }
        }

        $totalQuestions = $quiz->questions->count();
        $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100) : 0;
        $passed = $score >= $quiz->pass_percentage;

        return [
            'score' => $score,
            'passed' => $passed,
        ];
    }

    public function submitQuiz(int $quizId, array $selectedAnswers, int $userId)
    {
        $quiz = $this->loadQuiz($quizId);

        if (! $quiz) {
            return null;
        }

        $result = $this->calculateScore($quiz, $selectedAnswers);

        return QuizAttempt::create([
            'user_id' => $userId,
            'quiz_id' => $quizId,
            'score' => $result['score'],
            'passed' => $result['passed'],
            'submitted_at' => now(),
        ]);
    }
}
