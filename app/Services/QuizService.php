<?php

namespace App\Services;

use App\Models\Tenant\Quiz;
use App\Models\Tenant\Section;
use Illuminate\Support\Facades\DB;

class QuizService
{
    public function createQuizForSection(int $sectionId, array $data): Quiz
    {
        $section = Section::find($sectionId);

        if (! $section) {
            throw new \InvalidArgumentException('Section not found.');
        }

        if ($section->quiz) {
            throw new \LogicException('This section already has a quiz. Please edit the existing quiz instead.');
        }

        return Quiz::create(array_merge($data, ['section_id' => $sectionId]));
    }

    public function updateQuiz(Quiz $quiz, array $data): Quiz
    {
        $quiz->update($data);

        return $quiz;
    }

    public function deleteQuiz(Quiz $quiz): void
    {
        DB::transaction(function () use ($quiz) {
            $quiz->attempts()->delete();
            $quiz->questions()->each(fn ($question) => $question->options()->delete());
            $quiz->questions()->delete();
            $quiz->delete();
        });
    }

    public function findQuizWithRelations(int $id): ?Quiz
    {
        return Quiz::with('questions.options')->find($id);
    }

    public function findById(int $id): ?Quiz
    {
        return Quiz::find($id);
    }
}
