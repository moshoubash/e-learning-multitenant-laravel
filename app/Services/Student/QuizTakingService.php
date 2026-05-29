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
            $selected = $selectedAnswers[$question->id] ?? null;

            if ($selected === null) {
                continue;
            }

            if ($question->type === 'multiple') {
                // Multiple choice: selected is an array of option IDs
                $selectedArray = is_array($selected) ? $selected : [];
                $correctOptionIds = $question->options->where('is_correct', true)->pluck('id')->toArray();

                // Check if selected options exactly match correct options
                $isCorrect = !empty($selectedArray) &&
                             !empty($correctOptionIds) &&
                             count(array_diff($selectedArray, $correctOptionIds)) === 0 &&
                             count(array_diff($correctOptionIds, $selectedArray)) === 0;

                if ($isCorrect) {
                    $correctAnswers++;
                }
            } else {
                // Single choice: selected is a single option ID
                $correctOption = $question->options->where('is_correct', true)->first();

                if ($correctOption && $selected === $correctOption->id) {
                    $correctAnswers++;
                }
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
