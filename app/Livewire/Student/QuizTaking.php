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

    public function canTakeQuiz(): bool
    {
        if (!$this->quiz) {
            return false;
        }

        if (!$this->quiz->can_reattempt) {
            return false;
        }

        $attemptCount = QuizAttempt::where('user_id', auth()->id())
            ->where('quiz_id', $this->quiz->id)
            ->count();

        $maxAttempts = $this->quiz->max_attempts ?? 1;

        if ($attemptCount < $maxAttempts) {
            return true;
        }

        return false;
    }

    public function canReattempt(): bool
    {
        if (!$this->quiz) {
            return false;
        }

        $attemptCount = QuizAttempt::where('user_id', auth()->id())
            ->where('quiz_id', $this->quiz->id)
            ->count();

        $maxAttempts = $this->quiz->max_attempts ?? 1;

        return $attemptCount < $maxAttempts;
    }

    public function attemptsExhausted(): bool
    {
        if (!$this->quiz || !$this->quiz->can_reattempt) {
            return false;
        }

        $attemptCount = QuizAttempt::where('user_id', auth()->id())
            ->where('quiz_id', $this->quiz->id)
            ->count();

        return $attemptCount >= ($this->quiz->max_attempts ?? 1);
    }

    public function attemptCount(): int
    {
        if (!$this->quiz) {
            return 0;
        }

        return QuizAttempt::where('user_id', auth()->id())
            ->where('quiz_id', $this->quiz->id)
            ->count();
    }

    public function highestScore(): ?int
    {
        if (!$this->quiz) {
            return null;
        }

        return QuizAttempt::where('user_id', auth()->id())
            ->where('quiz_id', $this->quiz->id)
            ->max('score');
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
