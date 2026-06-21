<?php

namespace App\Actions\Quiz;

use App\Models\Tenant\QuizQuestion;
use App\Models\Tenant\QuizOption;

final class QuestionManager
{
    public function createQuestion(int $quizId, array $data): QuizQuestion
    {
        return QuizQuestion::create(array_merge($data, ['quiz_id' => $quizId]));
    }

    public function updateQuestion(QuizQuestion $question, array $data): QuizQuestion
    {
        $question->update($data);

        return $question;
    }

    public function deleteQuestion(QuizQuestion $question): void
    {
        $question->options()->delete();
        $question->delete();
    }

    public function findQuestionWithOptions(int $id): ?QuizQuestion
    {
        return QuizQuestion::with('options')->find($id);
    }

    public function findById(int $id): ?QuizQuestion
    {
        return QuizQuestion::find($id);
    }
}
