<?php

namespace App\Actions\Quiz;

use App\Models\Tenant\Quiz;
use App\Models\Tenant\Section;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class QuizManager
{
    public function getQuizzesForInstructor(int $instructorId, int $perPage = 10): LengthAwarePaginator
    {
        return Quiz::with(['section.course', 'questions.options'])
            ->whereHas('section.course', fn ($query) => $query->where('instructor_id', $instructorId))
            ->paginate($perPage);
    }

    public function getAllSectionsWithCourse(): Collection
    {
        return Section::with('course')
        ->doesntHave('quiz')
        ->get();
    }

    public function createQuiz(array $data): Quiz
    {
        return Quiz::create($data);
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
