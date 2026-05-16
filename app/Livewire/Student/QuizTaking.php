<?php

namespace App\Livewire\Student;

use App\Models\Tenant\Quiz;
use App\Models\Tenant\QuizAttempt;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class QuizTaking extends Component
{
    public $quizId;
    public $quiz = null;
    public $selectedAnswers = [];
    public $submitted = false;
    public $score = 0;
    public $passed = false;
    public $previousAttempt = null;

    public function mount($quizId)
    {
        $this->quizId = $quizId;
        $this->loadQuiz();
    }

    public function loadQuiz()
    {
        $this->quiz = Quiz::with(['questions.options', 'section'])->find($this->quizId);

        if (!$this->quiz) {
            return redirect()->route('tenant.student.courses');
        }

        // Check for previous attempts
        $this->previousAttempt = QuizAttempt::where('quiz_id', $this->quizId)
            ->where('user_id', auth()->id())
            ->latest('submitted_at')
            ->first();
    }

    public function selectOption($questionId, $optionId)
    {
        $this->selectedAnswers[$questionId] = $optionId;
    }

    public function isOptionSelected($questionId, $optionId)
    {
        return isset($this->selectedAnswers[$questionId]) && $this->selectedAnswers[$questionId] === $optionId;
    }

    public function submitQuiz()
    {
        if (!$this->quiz) {
            return;
        }

        // Validate all questions are answered
        foreach ($this->quiz->questions as $question) {
            if (!isset($this->selectedAnswers[$question->id])) {
                Toaster::error('Please answer all questions before submitting.');
                return;
            }
        }

        // Calculate score
        $correctAnswers = 0;
        foreach ($this->quiz->questions as $question) {
            $correctOption = $question->options->where('is_correct', true)->first();
            if ($correctOption && isset($this->selectedAnswers[$question->id])) {
                if ($this->selectedAnswers[$question->id] === $correctOption->id) {
                    $correctAnswers++;
                }
            }
        }

        $totalQuestions = $this->quiz->questions->count();
        $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100) : 0;
        $passed = $score >= $this->quiz->pass_percentage;

        // Save attempt
        QuizAttempt::create([
            'user_id' => auth()->id(),
            'quiz_id' => $this->quizId,
            'score' => $score,
            'passed' => $passed,
            'submitted_at' => now(),
        ]);

        $this->score = $score;
        $this->passed = $passed;
        $this->submitted = true;

        if ($passed) {
            Toaster::success("Congratulations! You passed with {$score}%!");
        } else {
            Toaster::warning("You scored {$score}%. You need {$this->quiz->pass_percentage}% to pass. Try again!");
        }
    }

    public function resetQuiz()
    {
        $this->selectedAnswers = [];
        $this->submitted = false;
        $this->score = 0;
        $this->passed = false;
    }

    public function render()
    {
        return view('livewire.student.quiz-taking');
    }
}