<?php

namespace App\Livewire\Instructor;

use App\Actions\Quiz\OptionManager;
use App\Actions\Quiz\QuestionManager;
use App\Actions\Quiz\QuizAttempts;
use App\Actions\Quiz\QuizManager;
use App\Models\Tenant\Quiz;
use App\Models\Tenant\QuizOption;
use App\Models\Tenant\QuizQuestion;
use App\Models\Tenant\Section;
use App\Services\AI\QuizGeneratorService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

#[Layout('layouts.instructor')]
class Quizzes extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Quiz modals
    public $showQuizCreateModal = false;

    public $showQuizEditModal = false;

    public $showQuizDeleteModal = false;

    // Question modals
    public $showQuestionCreateModal = false;

    public $showQuestionEditModal = false;

    public $showQuestionDeleteModal = false;

    // Option modals
    public $showOptionCreateModal = false;

    public $showOptionEditModal = false;

    public $showOptionDeleteModal = false;

    // Attempts modal
    public $showAttemptsModal = false;

    public $attemptsQuizId = null;

    // Expanded states
    public $expandedQuizzes = [];

    // Selected items
    public $selectedQuizId = null;

    public $selectedSectionId = null;

    public $selectedQuestionId = null;

    // Editing items
    public $deletingQuiz = null;

    public $editingQuiz = null;

    public $editingQuestion = null;

    public $editingOption = null;

    public $deletingQuestion = null;

    public $deletingOption = null;

    // Quiz form fields
    public $quizCreateTitle = '';

    public $quizCreateSectionId = '';

    public $quizCreatePassPercentage = 70;

    public $quizCreateCanReattempt = false;

    public $quizCreateMaxAttempts = 1;

    public $quizEditTitle = '';

    public $quizEditSectionId = '';

    public $quizEditPassPercentage = 70;

    public $quizEditCanReattempt = false;

    public $quizEditMaxAttempts = 1;

    // Question form fields
    public $questionCreateText = '';

    public $questionCreateType = 'single';

    public $questionCreateOrder = 0;

    public $questionEditText = '';

    public $questionEditType = 'single';

    public $questionEditOrder = 0;

    // AI Generation
    public $showAiGenerateModal = false;

    public $aiGenerateQuizId = null;

    public $aiGenerateTopic = '';

    public $aiGenerateCount = 3;

    public $aiGenerateTypes = ['single' => true, 'multiple' => true, 'true_false' => true];

    public $generating = false;

    // Option form fields
    public $optionCreateText = '';

    public $optionCreateIsCorrect = false;

    public $optionCreateQuestionType = 'single';

    public $optionCreateQuestionHasCorrect = false;

    public $optionEditText = '';

    public $optionEditIsCorrect = false;

    public $optionEditQuestionType = 'single';

    public $optionEditQuestionHasCorrect = false;

    //  QUIZ METHODS

    public function toggleQuizExpand($quizId)
    {
        if (in_array($quizId, $this->expandedQuizzes)) {
            $this->expandedQuizzes = array_filter($this->expandedQuizzes, fn ($id) => $id !== $quizId);
        } else {
            $this->expandedQuizzes[] = $quizId;
        }
    }

    public function isQuizExpanded($quizId)
    {
        return in_array($quizId, $this->expandedQuizzes);
    }

    public function openQuizCreateModal($sectionId = null)
    {
        $this->resetQuizCreateForm();
        $this->selectedSectionId = $sectionId;
        $this->showQuizCreateModal = true;
    }

    public function openQuizEditModal($id): void
    {
        $this->editingQuiz = $this->quizManager()->findQuizWithRelations($id);

        if (! $this->editingQuiz) {
            Toaster::error(__('messages.Quiz not found.'));

            return;
        }

        // Ensure the quiz belongs to the currently authenticated instructor
        $course = optional($this->editingQuiz->section)->course;
        if (! $course || $course->instructor_id !== Auth::id()) {
            Toaster::error('Unauthorized.');
            $this->editingQuiz = null;

            return;
        }

        $this->quizEditTitle = $this->editingQuiz->title;
        $this->quizEditSectionId = $this->editingQuiz->section_id;
        $this->quizEditPassPercentage = $this->editingQuiz->pass_percentage;
        $this->quizEditCanReattempt = $this->editingQuiz->can_reattempt ?? false;
        $this->quizEditMaxAttempts = $this->editingQuiz->max_attempts ?? 1;
        $this->showQuizEditModal = true;
    }

    public function openQuizDeleteModal($id): void
    {
        $this->deletingQuiz = Quiz::find($id);
        $this->showQuizDeleteModal = true;
    }

    public function closeQuizModal()
    {
        $this->showQuizCreateModal = false;
        $this->showQuizEditModal = false;
        $this->showQuizDeleteModal = false;
        $this->resetQuizFormFields();
    }

    public function resetQuizCreateForm()
    {
        $this->quizCreateTitle = '';
        $this->quizCreateSectionId = '';
        $this->quizCreatePassPercentage = 70;
        $this->quizCreateCanReattempt = false;
        $this->quizCreateMaxAttempts = 1;
    }

    public function resetQuizFormFields()
    {
        $this->editingQuiz = null;
        $this->deletingQuiz = null;
        $this->quizEditTitle = '';
        $this->quizEditSectionId = '';
        $this->quizEditPassPercentage = 70;
        $this->quizEditCanReattempt = false;
        $this->quizEditMaxAttempts = 1;
    }

    public function storeQuiz(): void
    {
        $this->validate($this->quizCreateRules());

        $section = Section::find($this->quizCreateSectionId);

        if ($section && $section->quiz) {
            Toaster::error('This section already has a quiz. Please edit the existing quiz instead.');
            $this->closeQuizModal();

            return;
        }

        $this->quizManager()->createQuiz([
            'title' => $this->quizCreateTitle,
            'section_id' => $this->quizCreateSectionId,
            'pass_percentage' => $this->quizCreatePassPercentage,
            'can_reattempt' => $this->quizCreateCanReattempt,
            'max_attempts' => $this->quizCreateCanReattempt ? $this->quizCreateMaxAttempts : 1,
        ]);

        $this->closeQuizModal();
        Toaster::success('Quiz created successfully!');
    }

    public function updateQuiz(): void
    {
        $this->validate($this->quizUpdateRules());

        if (! $this->editingQuiz) {
            Toaster::error(__('messages.Quiz not found.'));

            return;
        }

        $this->quizManager()->updateQuiz($this->editingQuiz, [
            'title' => $this->quizEditTitle,
            'section_id' => $this->quizEditSectionId,
            'pass_percentage' => $this->quizEditPassPercentage,
            'can_reattempt' => $this->quizEditCanReattempt,
            'max_attempts' => $this->quizEditCanReattempt ? $this->quizEditMaxAttempts : 1,
        ]);

        $this->closeQuizModal();
        Toaster::success('Quiz updated successfully!');
    }

    public function deleteQuiz(): void
    {
        if ($this->deletingQuiz) {
            $this->quizManager()->deleteQuiz($this->deletingQuiz);
            $this->closeQuizModal();
            Toaster::success('Quiz deleted successfully!');
        }
    }

    //  QUESTION METHODS

    public function openQuestionCreateModal($quizId)
    {
        $this->selectedQuizId = $quizId;
        $this->resetQuestionCreateForm();
        $this->showQuestionCreateModal = true;
    }

    public function openQuestionEditModal($id): void
    {
        $this->editingQuestion = $this->questionManager()->findQuestionWithOptions($id);

        if (! $this->editingQuestion) {
            Toaster::error(__('messages.Question not found.'));

            return;
        }

        $this->questionEditText = $this->editingQuestion->question;
        $this->questionEditType = $this->editingQuestion->type;
        $this->questionEditOrder = $this->editingQuestion->order;
        $this->showQuestionEditModal = true;
    }

    public function openQuestionDeleteModal($id): void
    {
        $this->deletingQuestion = QuizQuestion::find($id);
        $this->showQuestionDeleteModal = true;
    }

    public function closeQuestionModal(): void
    {
        $this->showQuestionCreateModal = false;
        $this->showQuestionEditModal = false;
        $this->showQuestionDeleteModal = false;
        $this->resetQuestionFormFields();
        $this->selectedQuizId = null;
    }

    public function resetQuestionCreateForm(): void
    {
        $this->questionCreateText = '';
        $this->questionCreateType = 'single';
        $this->questionCreateOrder = 0;
    }

    public function resetQuestionFormFields()
    {
        $this->editingQuestion = null;
        $this->deletingQuestion = null;
        $this->questionEditText = '';
        $this->questionEditType = 'single';
        $this->questionEditOrder = 0;
    }

    public function storeQuestion(): void
    {
        $this->validate($this->questionCreateRules());

        // Ensure the selected quiz belongs to the current instructor
        $quiz = $this->quizManager()->findById($this->selectedQuizId);
        if (! $quiz || optional(optional($quiz->section)->course)->instructor_id !== Auth::id()) {
            Toaster::error('Unauthorized.');
            $this->closeQuestionModal();

            return;
        }

        $question = $this->questionManager()->createQuestion($this->selectedQuizId, [
            'question' => $this->questionCreateText,
            'type' => $this->questionCreateType,
            'order' => $this->questionCreateOrder,
        ]);

        if ($this->questionCreateType === 'true_false') {
            $this->optionManager()->createOption($question->id, [
                'option_text' => 'True',
                'is_correct' => true,
            ]);
            $this->optionManager()->createOption($question->id, [
                'option_text' => 'False',
                'is_correct' => false,
            ]);
        }

        $this->closeQuestionModal();
        Toaster::success('Question created successfully!');
    }

    public function updateQuestion(): void
    {
        $this->validate($this->questionUpdateRules());

        if (! $this->editingQuestion) {
            Toaster::error(__('messages.Question not found.'));

            return;
        }

        $oldType = $this->editingQuestion->type;

        $this->questionManager()->updateQuestion($this->editingQuestion, [
            'question' => $this->questionEditText,
            'type' => $this->questionEditType,
            'order' => $this->questionEditOrder,
        ]);

        if ($this->questionEditType === 'true_false' && $oldType !== 'true_false') {
            $this->editingQuestion->options()->delete();
            $this->optionManager()->createOption($this->editingQuestion->id, [
                'option_text' => 'True',
                'is_correct' => true,
            ]);
            $this->optionManager()->createOption($this->editingQuestion->id, [
                'option_text' => 'False',
                'is_correct' => false,
            ]);
        }

        if ($this->questionEditType === 'single' && $this->optionManager()->countOptions($this->editingQuestion->id) > 1) {
            $correctCount = $this->editingQuestion->options()->where('is_correct', true)->count();
            if ($correctCount > 1) {
                $firstCorrect = $this->editingQuestion->options()->where('is_correct', true)->first();
                $this->editingQuestion->options()->where('is_correct', true)->where('id', '!=', $firstCorrect->id)->update(['is_correct' => false]);
            }
        }

        $this->closeQuestionModal();
        Toaster::success('Question updated successfully!');
    }

    public function deleteQuestion(): void
    {
        if ($this->deletingQuestion) {
            $this->questionManager()->deleteQuestion($this->deletingQuestion);
            $this->closeQuestionModal();
            Toaster::success('Question deleted successfully!');
        }
    }

    //  OPTION METHODS

    public function openOptionCreateModal($questionId): void
    {
        $question = $this->questionManager()->findQuestionWithOptions($questionId);

        if (! $question) {
            Toaster::error(__('messages.Question not found.'));

            return;
        }

        if ($question->type === 'true_false') {
            Toaster::error(__('messages.True/False questions cannot have additional options.'));

            return;
        }

        $this->selectedQuestionId = $questionId;
        $this->optionCreateQuestionType = $question->type;
        $this->optionCreateQuestionHasCorrect = $question->options->where('is_correct', true)->count() > 0;
        $this->resetOptionCreateForm();
        $this->showOptionCreateModal = true;
    }

    public function openOptionEditModal($id): void
    {
        $this->editingOption = $this->optionManager()->findById($id);

        if (! $this->editingOption) {
            Toaster::error(__('messages.Option not found.'));

            return;
        }

        $this->editingOption->loadMissing('question.options');

        $this->optionEditText = $this->editingOption->option_text;
        $this->optionEditIsCorrect = $this->editingOption->is_correct;
        $this->optionEditQuestionType = $this->editingOption->question->type;
        $this->optionEditQuestionHasCorrect = $this->editingOption->question->options
            ->where('is_correct', true)
            ->where('id', '!=', $id)
            ->count() > 0;
        $this->showOptionEditModal = true;
    }

    public function openOptionDeleteModal($id): void
    {
        $this->deletingOption = QuizOption::find($id);
        $this->showOptionDeleteModal = true;
    }

    public function closeOptionModal(): void
    {
        $this->showOptionCreateModal = false;
        $this->showOptionEditModal = false;
        $this->showOptionDeleteModal = false;
        $this->resetOptionFormFields();
        $this->selectedQuestionId = null;
    }

    public function resetOptionCreateForm(): void
    {
        $this->optionCreateText = '';
        $this->optionCreateIsCorrect = false;
    }

    public function resetOptionFormFields()
    {
        $this->editingOption = null;
        $this->deletingOption = null;
        $this->optionEditText = '';
        $this->optionEditIsCorrect = false;
        $this->optionEditQuestionType = 'single';
        $this->optionEditQuestionHasCorrect = false;
    }

    public function storeOption(): void
    {
        $this->validate($this->optionCreateRules());

        $question = $this->questionManager()->findById($this->selectedQuestionId);

        if ($question && $question->type === 'single' && $this->optionCreateIsCorrect) {
            $this->optionManager()->unmarkAllCorrect($this->selectedQuestionId);
        }

        if ($question && $question->type === 'single' && $this->optionManager()->hasCorrectOption($this->selectedQuestionId)) {
            $this->optionCreateIsCorrect = false;
        }

        $this->optionManager()->createOption($this->selectedQuestionId, [
            'option_text' => $this->optionCreateText,
            'is_correct' => $this->optionCreateIsCorrect,
        ]);

        $this->closeOptionModal();
        Toaster::success('Option created successfully!');
    }

    public function updateOption(): void
    {
        $this->validate($this->optionUpdateRules());

        if (! $this->editingOption) {
            Toaster::error(__('messages.Option not found.'));

            return;
        }

        $this->editingOption->loadMissing('question');

        if ($this->editingOption->question->type === 'single' && $this->optionEditIsCorrect) {
            $this->optionManager()->unmarkAllCorrect($this->editingOption->question_id);
        }

        $this->optionManager()->updateOption($this->editingOption, [
            'option_text' => $this->optionEditText,
            'is_correct' => $this->optionEditIsCorrect,
        ]);

        $this->closeOptionModal();
        Toaster::success('Option updated successfully!');
    }

    public function deleteOption(): void
    {
        if ($this->deletingOption) {
            // Ensure the option belongs to a question/quiz owned by the current instructor
            $this->deletingOption->loadMissing('question.quiz.section.course');
            $course = optional(optional(optional($this->deletingOption->question)->quiz)->section)->course;
            if (! $course || $course->instructor_id !== Auth::id()) {
                Toaster::error('Unauthorized.');
                $this->closeOptionModal();

                return;
            }

            $this->optionManager()->deleteOption($this->deletingOption);
            $this->closeOptionModal();
            Toaster::success('Option deleted successfully!');
        }
    }

    //  ATTEMPTS METHODS

    public function openAttemptsModal($quizId)
    {
        $this->attemptsQuizId = $quizId;
        $this->showAttemptsModal = true;
    }

    public function closeAttemptsModal()
    {
        $this->showAttemptsModal = false;
        $this->attemptsQuizId = null;
    }

    //  AI GENERATION METHODS

    public function openAiGenerateModal($quizId)
    {
        $quiz = $this->quizManager()->findById($quizId);

        if (! $quiz || optional(optional($quiz->section)->course)->instructor_id !== Auth::id()) {
            Toaster::error('Unauthorized.');

            return;
        }

        $this->aiGenerateQuizId = $quizId;
        $this->aiGenerateTopic = $quiz->title;
        $this->aiGenerateCount = 3;
        $this->aiGenerateTypes = ['single' => true, 'multiple' => true, 'true_false' => true];
        $this->showAiGenerateModal = true;
    }

    public function closeAiGenerateModal()
    {
        $this->showAiGenerateModal = false;
        $this->aiGenerateQuizId = null;
        $this->aiGenerateTopic = '';
        $this->aiGenerateCount = 3;
        $this->aiGenerateTypes = ['single' => true, 'multiple' => true, 'true_false' => true];
        $this->generating = false;
    }

    public function generateWithAI()
    {
        $this->validate([
            'aiGenerateTopic' => 'required|string|max:500',
            'aiGenerateCount' => 'required|integer|min:1|max:10',
        ]);

        $selectedTypes = array_keys(array_filter($this->aiGenerateTypes));
        if (empty($selectedTypes)) {
            Toaster::error('Please select at least one question type.');

            return;
        }

        $this->generating = true;

        try {
            $questions = app(QuizGeneratorService::class)->generate(
                $this->aiGenerateTopic,
                $this->aiGenerateCount,
                $selectedTypes,
            );

            if (empty($questions)) {
                Toaster::error('AI returned no questions. Try a different topic.');
                $this->generating = false;

                return;
            }

            $existingCount = QuizQuestion::where('quiz_id', $this->aiGenerateQuizId)->count();

            foreach ($questions as $i => $q) {
                if (empty($q['options'])) {
                    continue;
                }

                $question = $this->questionManager()->createQuestion($this->aiGenerateQuizId, [
                    'question' => $q['question'],
                    'type' => $q['type'] ?? 'single',
                    'order' => $existingCount + $i + 1,
                ]);

                foreach ($q['options'] as $opt) {
                    $this->optionManager()->createOption($question->id, [
                        'option_text' => $opt['text'],
                        'is_correct' => $opt['correct'] ?? false,
                    ]);
                }
            }

            $this->closeAiGenerateModal();
            Toaster::success(count($questions).' questions generated successfully!');
        } catch (\RuntimeException $e) {
            Toaster::error($e->getMessage());
        } finally {
            $this->generating = false;
        }
    }

    public function getAttemptsProperty()
    {
        if (! $this->attemptsQuizId) {
            return collect();
        }

        return $this->attemptsManager()->getAttemptsForQuiz($this->attemptsQuizId);
    }

    public function render()
    {
        $quizzes = $this->quizManager()->getQuizzesForInstructor(Auth::id(), 10);
        $sections = $this->quizManager()->getAllSectionsWithCourse();

        return view('livewire.instructor.quizzes', [
            'quizzes' => $quizzes,
            'sections' => $sections,
        ]);
    }

    protected function quizCreateRules(): array
    {
        return [
            'quizCreateTitle' => 'required|string|max:255',
            'quizCreateSectionId' => 'required|exists:sections,id',
            'quizCreatePassPercentage' => 'required|integer|min:1|max:100',
            'quizCreateMaxAttempts' => 'nullable|integer|min:1|max:100',
        ];
    }

    protected function quizUpdateRules(): array
    {
        return [
            'quizEditTitle' => 'required|string|max:255',
            'quizEditSectionId' => 'required|exists:sections,id',
            'quizEditPassPercentage' => 'required|integer|min:1|max:100',
            'quizEditMaxAttempts' => 'nullable|integer|min:1|max:100',
        ];
    }

    protected function questionCreateRules(): array
    {
        return [
            'questionCreateText' => 'required|string',
            'questionCreateType' => 'required|in:single,multiple,true_false',
            'questionCreateOrder' => 'required|integer|min:0',
        ];
    }

    protected function questionUpdateRules(): array
    {
        return [
            'questionEditText' => 'required|string',
            'questionEditType' => 'required|in:single,multiple,true_false',
            'questionEditOrder' => 'required|integer|min:0',
        ];
    }

    protected function optionCreateRules(): array
    {
        return [
            'optionCreateText' => 'required|string',
            'optionCreateIsCorrect' => 'boolean',
        ];
    }

    protected function optionUpdateRules(): array
    {
        return [
            'optionEditText' => 'required|string',
            'optionEditIsCorrect' => 'boolean',
        ];
    }

    protected function quizManager(): QuizManager
    {
        return new QuizManager;
    }

    protected function questionManager(): QuestionManager
    {
        return new QuestionManager;
    }

    protected function optionManager(): OptionManager
    {
        return new OptionManager;
    }

    protected function attemptsManager(): QuizAttempts
    {
        return new QuizAttempts;
    }
}
