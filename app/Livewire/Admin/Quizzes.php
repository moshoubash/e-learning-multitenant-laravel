<?php

namespace App\Livewire\Admin;

use App\Models\Tenant\Quiz;
use App\Models\Tenant\QuizQuestion;
use App\Models\Tenant\QuizOption;
use App\Models\Tenant\QuizAttempt;
use App\Models\Tenant\Section;
use App\Services\Admin\QuizzesService;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Quizzes extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Modal states
    public $showEditQuizModal = false;
    public $showAttemptsModal = false;
    public $showQuestionCreateModal = false;
    public $showQuestionEditModal = false;
    public $showQuestionDeleteModal = false;
    public $showOptionCreateModal = false;
    public $showOptionEditModal = false;
    public $showOptionDeleteModal = false;

    // Selected items
    public $editingQuiz = null;
    public $selectedQuizId = null;
    public $selectedQuestionId = null;

    // Delete tracking
    public $deletingQuestion = null;
    public $deletingOption = null;

    // Quiz edit fields
    public $editTitle = '';
    public $editSectionId = '';
    public $editPassPercentage = 70;

    // Question fields
    public $questionCreateText = '';
    public $questionCreateType = 'single';
    public $questionCreateOrder = 0;

    public $questionEditText = '';
    public $questionEditType = 'single';
    public $questionEditOrder = 0;

    // Option fields
    public $optionCreateText = '';
    public $optionCreateIsCorrect = false;

    public $optionEditText = '';
    public $optionEditIsCorrect = false;

    public $editingOption = null;
    public $editingQuestion = null;


    public function openEditQuizModal($id)
    {
        $this->editingQuiz = $this->quizzesService()->findQuizWithRelations($id);

        if (! $this->editingQuiz) {
            Toaster::error('Quiz not found.');
            return;
        }

        $this->editTitle = $this->editingQuiz->title;
        $this->editSectionId = $this->editingQuiz->section_id;
        $this->editPassPercentage = $this->editingQuiz->pass_percentage;
        $this->showEditQuizModal = true;
    }

    public function openAttemptsModal($id)
    {
        $this->selectedQuizId = $id;
        $this->showAttemptsModal = true;
    }

    public function closeModals()
    {
        $this->showEditQuizModal = false;
        $this->showAttemptsModal = false;
        $this->showQuestionCreateModal = false;
        $this->showQuestionEditModal = false;
        $this->showQuestionDeleteModal = false;
        $this->showOptionCreateModal = false;
        $this->showOptionEditModal = false;
        $this->showOptionDeleteModal = false;
        $this->resetFormFields();
    }

    public function resetFormFields()
    {
        $this->editingQuiz = null;
        $this->selectedQuizId = null;
        $this->selectedQuestionId = null;
        $this->deletingQuestion = null;
        $this->deletingOption = null;
        $this->editTitle = '';
        $this->editSectionId = '';
        $this->editPassPercentage = 70;
        $this->questionCreateText = '';
        $this->questionCreateType = 'single';
        $this->questionCreateOrder = 0;
        $this->questionEditText = '';
        $this->questionEditType = 'single';
        $this->questionEditOrder = 0;
        $this->optionCreateText = '';
        $this->optionCreateIsCorrect = false;
        $this->optionEditText = '';
        $this->optionEditIsCorrect = false;
    }

    public function updateQuiz()
    {
        $this->validate([
            'editTitle' => 'required|string|max:255',
            'editSectionId' => 'required|exists:sections,id',
            'editPassPercentage' => 'required|integer|min:1|max:100',
        ]);

        $this->quizzesService()->updateQuiz($this->editingQuiz, [
            'title' => $this->editTitle,
            'section_id' => $this->editSectionId,
            'pass_percentage' => $this->editPassPercentage,
        ]);

        $this->closeModals();
        Toaster::success('messages.Quiz updated successfully!');
    }

    // Question methods
    public function openQuestionCreateModal($quizId)
    {
        $this->selectedQuizId = $quizId;
        $this->questionCreateText = '';
        $this->questionCreateType = 'single';
        $this->questionCreateOrder = 0;
        $this->showQuestionCreateModal = true;
    }

    public function openQuestionEditModal($id)
    {
        $this->editingQuestion = $this->quizzesService()->findQuestionWithOptions($id);

        if (! $this->editingQuestion) {
            Toaster::error('Question not found.');
            return;
        }

        $this->questionEditText = $this->editingQuestion->question;
        $this->questionEditType = $this->editingQuestion->type;
        $this->questionEditOrder = $this->editingQuestion->order;
        $this->showQuestionEditModal = true;
    }

    public function openQuestionDeleteModal($id)
    {
        $this->deletingQuestion = $this->quizzesService()->findQuestionWithOptions($id);
        $this->showQuestionDeleteModal = true;
    }

    public function storeQuestion()
    {
        $this->validate([
            'questionCreateText' => 'required|string',
            'questionCreateType' => 'required|in:single,multiple,true_false',
            'questionCreateOrder' => 'required|integer|min:0',
        ]);

        $this->quizzesService()->createQuestion($this->selectedQuizId, [
            'question' => $this->questionCreateText,
            'type' => $this->questionCreateType,
            'order' => $this->questionCreateOrder,
        ]);

        $this->editingQuiz = $this->quizzesService()->findQuizWithRelations($this->editingQuiz->id);
        $this->showQuestionCreateModal = false;
        Toaster::success('messages.Question created successfully!');
    }

    public function updateQuestion()
    {
        $this->validate([
            'questionEditText' => 'required|string',
            'questionEditType' => 'required|in:single,multiple,true_false',
            'questionEditOrder' => 'required|integer|min:0',
        ]);

        $this->quizzesService()->updateQuestion($this->editingQuestion, [
            'question' => $this->questionEditText,
            'type' => $this->questionEditType,
            'order' => $this->questionEditOrder,
        ]);

        $this->editingQuiz = $this->quizzesService()->findQuizWithRelations($this->editingQuiz->id);
        $this->showQuestionEditModal = false;
        Toaster::success('messages.Question updated successfully!');
    }

    public function deleteQuestion()
    {
        if ($this->deletingQuestion) {
            $this->quizzesService()->deleteQuestion($this->deletingQuestion);
            $this->editingQuiz = $this->quizzesService()->findQuizWithRelations($this->editingQuiz->id);
            $this->showQuestionDeleteModal = false;
            Toaster::success('messages.Question deleted successfully!');
        }
    }

    // Option methods
    public function openOptionCreateModal($questionId)
    {
        $this->selectedQuestionId = $questionId;
        $this->optionCreateText = '';
        $this->optionCreateIsCorrect = false;
        $this->showOptionCreateModal = true;
    }

    public function openOptionEditModal($id)
    {
        $this->editingOption = $this->quizzesService()->findOptionById($id);

        if (! $this->editingOption) {
            Toaster::error('Option not found.');
            return;
        }

        $this->optionEditText = $this->editingOption->option_text;
        $this->optionEditIsCorrect = $this->editingOption->is_correct;
        $this->showOptionEditModal = true;
    }

    public function openOptionDeleteModal($id)
    {
        $this->deletingOption = $this->quizzesService()->findOptionById($id);
        $this->showOptionDeleteModal = true;
    }

    public function storeOption()
    {
        $this->validate([
            'optionCreateText' => 'required|string',
            'optionCreateIsCorrect' => 'boolean',
        ]);

        $this->quizzesService()->createOption($this->selectedQuestionId, [
            'option_text' => $this->optionCreateText,
            'is_correct' => $this->optionCreateIsCorrect,
        ]);

        $this->editingQuiz = $this->quizzesService()->findQuizWithRelations($this->editingQuiz->id);
        $this->showOptionCreateModal = false;
        Toaster::success('messages.Option created successfully!');
    }

    public function updateOption()
    {
        $this->validate([
            'optionEditText' => 'required|string',
            'optionEditIsCorrect' => 'boolean',
        ]);

        $this->quizzesService()->updateOption($this->editingOption, [
            'option_text' => $this->optionEditText,
            'is_correct' => $this->optionEditIsCorrect,
        ]);

        $this->editingQuiz = $this->quizzesService()->findQuizWithRelations($this->editingQuiz->id);
        $this->showOptionEditModal = false;
        Toaster::success('messages.Option updated successfully!');
    }

    public function deleteOption()
    {
        if ($this->deletingOption) {
            $this->quizzesService()->deleteOption($this->deletingOption);
            $this->editingQuiz = $this->quizzesService()->findQuizWithRelations($this->editingQuiz->id);
            $this->showOptionDeleteModal = false;
            Toaster::success('messages.Option deleted successfully!');
        }
    }

    public function getAttemptsProperty()
    {
        return $this->quizzesService()->getAttemptsForQuiz($this->selectedQuizId);
    }

    public function getSections()
    {
        return $this->quizzesService()->getSections();
    }

    public function render()
    {
        $quizzes = $this->quizzesService()->getPaginatedQuizzes(10);

        $sections = $this->getSections();

        return view('livewire.admin.quizzes', [
            'quizzes' => $quizzes,
            'sections' => $sections,
        ]);
    }

    protected function quizzesService(): QuizzesService
    {
        return new QuizzesService();
    }
}
