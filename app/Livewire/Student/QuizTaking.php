<?php

namespace App\Livewire\Student;

use App\Models\Tenant\Quiz;
use App\Models\Tenant\QuizAttempt;
use App\Services\Student\QuizTakingService;
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
        $this->quiz = $this->quizTakingService()->loadQuiz($this->quizId);

        if (!$this->quiz) {
            return redirect()->route('tenant.student.courses');
        }

        $this->previousAttempt = $this->quizTakingService()->getPreviousAttempt($this->quizId, auth()->id());
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
        $result = $this->quizTakingService()->calculateScore($this->quiz, $this->selectedAnswers);
        $attempt = $this->quizTakingService()->submitQuiz($this->quizId, $this->selectedAnswers, auth()->id());

        $this->score = $result['score'];
        $this->passed = $result['passed'];
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

    protected function quizTakingService(): QuizTakingService
    {
        return new QuizTakingService();
    }
}
