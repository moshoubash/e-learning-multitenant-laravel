<?php

namespace App\Actions\Quiz;

use App\Models\Tenant\QuizOption;

final class OptionManager
{
    public function createOption(int $questionId, array $data): QuizOption
    {
        return QuizOption::create(array_merge($data, ['question_id' => $questionId]));
    }

    public function updateOption(QuizOption $option, array $data): QuizOption
    {
        $option->update($data);

        return $option;
    }

    public function deleteOption(QuizOption $option): void
    {
        $option->delete();
    }

    public function findById(int $id): ?QuizOption
    {
        return QuizOption::find($id);
    }

    public function hasCorrectOption(int $questionId): bool
    {
        return QuizOption::where('question_id', $questionId)
            ->where('is_correct', true)
            ->exists();
    }

    public function unmarkAllCorrect(int $questionId): void
    {
        QuizOption::where('question_id', $questionId)
            ->where('is_correct', true)
            ->update(['is_correct' => false]);
    }

    public function countOptions(int $questionId): int
    {
        return QuizOption::where('question_id', $questionId)->count();
    }
}
