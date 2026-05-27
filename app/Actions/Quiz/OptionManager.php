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
}
