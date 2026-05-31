<?php

namespace App\Livewire\Student;

use App\Models\Tenant\Quiz;
use App\Models\Tenant\QuizAttempt;
use App\Services\Student\QuizTakingService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

#[Layout('layouts.student')]
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

    public function canReattempt(): bool
    {
        if (!$this->quiz) {
            return false;
        }
        return $this->quiz->can_reattempt ?? false;
    }

    public function canTakeQuiz(): bool
    {
        if (!$this->quiz) {
            return false;
        }

        // If no previous attempt, student can take the quiz
        if (!$this->previousAttempt) {
            return true;
        }

        // If previous attempt exists, check if re-attempt is allowed
        if ($this->canReattempt()) {
            return true;
        }

        // If passed and re-attempt not allowed, cannot take quiz
        if ($this->previousAttempt->passed) {
            return false;
        }

        return true;
    }

    public function loadQuiz()
    {
        $this->quiz = $this->quizTakingService()->loadQuiz($this->quizId);

        if (!$this->quiz) {
            return redirect()->route('tenant.student.courses');
        }

        $this->previousAttempt = $this->quizTakingService()->getPreviousAttempt($this->quizId, auth()->id());
    }

    public function selectOption($questionId, $optionId, bool $isMultiple = false)
    {
        if ($isMultiple) {
            // Multiple choice: toggle option in/out of array
            if (!isset($this->selectedAnswers[$questionId])) {
                $this->selectedAnswers[$questionId] = [];
            }

            $index = array_search($optionId, $this->selectedAnswers[$questionId]);
            if ($index !== false) {
                // Option already selected, remove it
                unset($this->selectedAnswers[$questionId][$index]);
                $this->selectedAnswers[$questionId] = array_values($this->selectedAnswers[$questionId]);
            } else {
                // Option not selected, add it
                $this->selectedAnswers[$questionId][] = $optionId;
            }
        } else {
            // Single choice: replace with new selection
            $this->selectedAnswers[$questionId] = $optionId;
        }
    }

    public function isOptionSelected($questionId, $optionId)
    {
        if (!isset($this->selectedAnswers[$questionId])) {
            return false;
        }

        // Handle both array (multiple) and single value (single choice)
        if (is_array($this->selectedAnswers[$questionId])) {
            return in_array($optionId, $this->selectedAnswers[$questionId]);
        }

        return $this->selectedAnswers[$questionId] === $optionId;
    }

    public function submitQuiz()
    {
        if (!$this->quiz) {
            return;
        }

        // Validate all questions are answered
        foreach ($this->quiz->questions as $question) {
            $answer = $this->selectedAnswers[$question->id] ?? null;

            $isAnswered = false;
            if ($question->type === 'multiple') {
                $isAnswered = is_array($answer) && count($answer) > 0;
            } else {
                $isAnswered = $answer !== null;
            }

            if (!$isAnswered) {
                Toaster::error('Please answer all questions before submitting.');
                return;
            }
        }

        // Calculate score
        $result = $this->quizTakingService()->calculateScore($this->quiz, $this->selectedAnswers);
        $attempt = $this->quizTakingService()->submitQuiz($this->quizId, $this->selectedAnswers, auth()->id());

        $this->score = $result['score'];
        $this->passed = $result['passed'];
        $this->submitted = true;

        if ($this->passed) {
            Toaster::success("Congratulations! You passed with {$this->score}%!");
        } else {
            Toaster::warning("You scored {$this->score}%. You need {$this->quiz->pass_percentage}% to pass. Try again!");
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

    protected function quizTakingService(): QuizTakingService
    {
        return new QuizTakingService();
    }
}
