<?php

namespace App\Livewire\Admin;

use App\Models\Tenant\Quiz;
use App\Models\Tenant\QuizQuestion;
use App\Models\Tenant\QuizOption;
use App\Models\Tenant\QuizAttempt;
use App\Models\Tenant\Section;
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

    public function openEditQuizModal($id)
    {
        $this->editingQuiz = Quiz::with(['questions.options', 'section.course'])->find($id);
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

        $this->editingQuiz->title = $this->editTitle;
        $this->editingQuiz->section_id = $this->editSectionId;
        $this->editingQuiz->pass_percentage = $this->editPassPercentage;
        $this->editingQuiz->save();

        $this->closeModals();
        Toaster::success('Quiz updated successfully!');
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
        $this->editingQuestion = QuizQuestion::with('options')->find($id);
        $this->questionEditText = $this->editingQuestion->question;
        $this->questionEditType = $this->editingQuestion->type;
        $this->questionEditOrder = $this->editingQuestion->order;
        $this->showQuestionEditModal = true;
    }

    public function openQuestionDeleteModal($id)
    {
        $this->deletingQuestion = QuizQuestion::find($id);
        $this->showQuestionDeleteModal = true;
    }

    public function storeQuestion()
    {
        $this->validate([
            'questionCreateText' => 'required|string',
            'questionCreateType' => 'required|in:single,multiple,true_false',
            'questionCreateOrder' => 'required|integer|min:0',
        ]);

        QuizQuestion::create([
            'quiz_id' => $this->selectedQuizId,
            'question' => $this->questionCreateText,
            'type' => $this->questionCreateType,
            'order' => $this->questionCreateOrder,
        ]);

        // Refresh the quiz data
        $this->editingQuiz = Quiz::with(['questions.options', 'section.course'])->find($this->editingQuiz->id);
        $this->showQuestionCreateModal = false;
        Toaster::success('Question created successfully!');
    }

    public function updateQuestion()
    {
        $this->validate([
            'questionEditText' => 'required|string',
            'questionEditType' => 'required|in:single,multiple,true_false',
            'questionEditOrder' => 'required|integer|min:0',
        ]);

        $this->editingQuestion->question = $this->questionEditText;
        $this->editingQuestion->type = $this->questionEditType;
        $this->editingQuestion->order = $this->questionEditOrder;
        $this->editingQuestion->save();

        // Refresh the quiz data
        $this->editingQuiz = Quiz::with(['questions.options', 'section.course'])->find($this->editingQuiz->id);
        $this->showQuestionEditModal = false;
        Toaster::success('Question updated successfully!');
    }

    public function deleteQuestion()
    {
        if ($this->deletingQuestion) {
            $this->deletingQuestion->options()->delete();
            $this->deletingQuestion->delete();
            // Refresh the quiz data
            $this->editingQuiz = Quiz::with(['questions.options', 'section.course'])->find($this->editingQuiz->id);
            $this->showQuestionDeleteModal = false;
            Toaster::success('Question deleted successfully!');
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
        $this->editingOption = QuizOption::find($id);
        $this->optionEditText = $this->editingOption->option_text;
        $this->optionEditIsCorrect = $this->editingOption->is_correct;
        $this->showOptionEditModal = true;
    }

    public function openOptionDeleteModal($id)
    {
        $this->deletingOption = QuizOption::find($id);
        $this->showOptionDeleteModal = true;
    }

    public function storeOption()
    {
        $this->validate([
            'optionCreateText' => 'required|string',
            'optionCreateIsCorrect' => 'boolean',
        ]);

        QuizOption::create([
            'question_id' => $this->selectedQuestionId,
            'option_text' => $this->optionCreateText,
            'is_correct' => $this->optionCreateIsCorrect,
        ]);

        // Refresh the quiz data
        $this->editingQuiz = Quiz::with(['questions.options', 'section.course'])->find($this->editingQuiz->id);
        $this->showOptionCreateModal = false;
        Toaster::success('Option created successfully!');
    }

    public function updateOption()
    {
        $this->validate([
            'optionEditText' => 'required|string',
            'optionEditIsCorrect' => 'boolean',
        ]);

        $this->editingOption->option_text = $this->optionEditText;
        $this->editingOption->is_correct = $this->optionEditIsCorrect;
        $this->editingOption->save();

        // Refresh the quiz data
        $this->editingQuiz = Quiz::with(['questions.options', 'section.course'])->find($this->editingQuiz->id);
        $this->showOptionEditModal = false;
        Toaster::success('Option updated successfully!');
    }

    public function deleteOption()
    {
        if ($this->deletingOption) {
            $this->deletingOption->delete();
            // Refresh the quiz data
            $this->editingQuiz = Quiz::with(['questions.options', 'section.course'])->find($this->editingQuiz->id);
            $this->showOptionDeleteModal = false;
            Toaster::success('Option deleted successfully!');
        }
    }

    public function getAttemptsProperty()
    {
        return QuizAttempt::where('quiz_id', $this->selectedQuizId)
            ->with('user')
            ->orderBy('submitted_at', 'desc')
            ->get();
    }

    public function getSections()
    {
        return Section::with('course')->get();
    }

    public function render()
    {
        $quizzes = Quiz::with(['section.course', 'questions'])
            ->paginate(10);

        $sections = $this->getSections();

        return view('livewire.admin.quizzes', [
            'quizzes' => $quizzes,
            'sections' => $sections,
        ]);
    }
}