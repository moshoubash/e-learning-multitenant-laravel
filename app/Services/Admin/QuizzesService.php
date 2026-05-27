<?php

namespace App\Services\Admin;

use App\Models\Tenant\Quiz;
use App\Models\Tenant\QuizQuestion;
use App\Models\Tenant\QuizOption;
use App\Models\Tenant\QuizAttempt;
use App\Models\Tenant\Section;

class QuizzesService
{
    public function findQuizWithRelations(int $id): ?Quiz
    {
        return Quiz::with(['questions.options', 'section.course'])->find($id);
    }

    public function updateQuiz(Quiz $quiz, array $data): Quiz
    {
        $quiz->update($data);

        return $quiz;
    }

    public function createQuestion(int $quizId, array $data): QuizQuestion
    {
        return QuizQuestion::create(array_merge($data, ['quiz_id' => $quizId]));
    }

    public function findQuestionWithOptions(int $id): ?QuizQuestion
    {
        return QuizQuestion::with('options')->find($id);
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

    public function createOption(int $questionId, array $data): QuizOption
    {
        return QuizOption::create(array_merge($data, ['question_id' => $questionId]));
    }

    public function findOptionById(int $id): ?QuizOption
    {
        return QuizOption::find($id);
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

    public function getAttemptsForQuiz(int $quizId)
    {
        return QuizAttempt::where('quiz_id', $quizId)
            ->with('user')
            ->orderBy('submitted_at', 'desc')
            ->get();
    }

    public function getSections()
    {
        return Section::with('course')->get();
    }

    public function getPaginatedQuizzes(int $perPage = 10)
    {
        return Quiz::with(['section.course', 'questions'])->paginate($perPage);
    }
}
