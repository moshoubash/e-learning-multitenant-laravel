<?php

namespace App\Livewire\Instructor;

use App\Models\Tenant\Quiz;
use App\Models\Tenant\QuizQuestion;
use App\Models\Tenant\QuizOption;
use App\Models\Tenant\Section;
use App\Models\Tenant\Course;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

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

    public $quizEditTitle = '';
    public $quizEditSectionId = '';
    public $quizEditPassPercentage = 70;

    // Question form fields
    public $questionCreateText = '';
    public $questionCreateType = 'single';
    public $questionCreateOrder = 0;

    public $questionEditText = '';
    public $questionEditType = 'single';
    public $questionEditOrder = 0;

    // Option form fields
    public $optionCreateText = '';
    public $optionCreateIsCorrect = false;

    public $optionEditText = '';
    public $optionEditIsCorrect = false;

    //  QUIZ METHODS 

    public function toggleQuizExpand($quizId)
    {
        if (in_array($quizId, $this->expandedQuizzes)) {
            $this->expandedQuizzes = array_filter($this->expandedQuizzes, fn($id) => $id !== $quizId);
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

    public function openQuizEditModal($id)
    {
        $this->editingQuiz = Quiz::with('questions.options')->find($id);
        $this->quizEditTitle = $this->editingQuiz->title;
        $this->quizEditSectionId = $this->editingQuiz->section_id;
        $this->quizEditPassPercentage = $this->editingQuiz->pass_percentage;
        $this->showQuizEditModal = true;
    }

    public function openQuizDeleteModal($id)
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
    }

    public function resetQuizFormFields()
    {
        $this->editingQuiz = null;
        $this->deletingQuiz = null;
        $this->quizEditTitle = '';
        $this->quizEditSectionId = '';
        $this->quizEditPassPercentage = 70;
    }

    public function storeQuiz()
    {
        $this->validate([
            'quizCreateTitle' => 'required|string|max:255',
            'quizCreateSectionId' => 'required|exists:sections,id',
            'quizCreatePassPercentage' => 'required|integer|min:1|max:100',
        ]);

        // Check if section already has a quiz
        $section = Section::find($this->quizCreateSectionId);
        if ($section && $section->quiz) {
            Toaster::error('This section already has a quiz. Please edit the existing quiz instead.');
            $this->closeQuizModal();
            return;
        }

        Quiz::create([
            'title' => $this->quizCreateTitle,
            'section_id' => $this->quizCreateSectionId,
            'pass_percentage' => $this->quizCreatePassPercentage,
        ]);

        $this->closeQuizModal();
        Toaster::success('Quiz created successfully!');
    }

    public function updateQuiz()
    {
        $this->validate([
            'quizEditTitle' => 'required|string|max:255',
            'quizEditSectionId' => 'required|exists:sections,id',
            'quizEditPassPercentage' => 'required|integer|min:1|max:100',
        ]);

        $this->editingQuiz->title = $this->quizEditTitle;
        $this->editingQuiz->section_id = $this->quizEditSectionId;
        $this->editingQuiz->pass_percentage = $this->quizEditPassPercentage;
        $this->editingQuiz->save();

        $this->closeQuizModal();
        Toaster::success('Quiz updated successfully!');
    }

    public function deleteQuiz()
    {
        if ($this->deletingQuiz) {
            $this->deletingQuiz->questions()->each(function ($question) {
                $question->options()->delete();
            });
            $this->deletingQuiz->questions()->delete();
            $this->deletingQuiz->delete();
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

    public function closeQuestionModal()
    {
        $this->showQuestionCreateModal = false;
        $this->showQuestionEditModal = false;
        $this->showQuestionDeleteModal = false;
        $this->resetQuestionFormFields();
    }

    public function resetQuestionCreateForm()
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

        $this->closeQuestionModal();
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

        $this->closeQuestionModal();
        Toaster::success('Question updated successfully!');
    }

    public function deleteQuestion()
    {
        if ($this->deletingQuestion) {
            $this->deletingQuestion->options()->delete();
            $this->deletingQuestion->delete();
            $this->closeQuestionModal();
            Toaster::success('Question deleted successfully!');
        }
    }

    //  OPTION METHODS 

    public function openOptionCreateModal($questionId)
    {
        $this->selectedSectionId = $questionId;
        $this->resetOptionCreateForm();
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

    public function closeOptionModal()
    {
        $this->showOptionCreateModal = false;
        $this->showOptionEditModal = false;
        $this->showOptionDeleteModal = false;
        $this->resetOptionFormFields();
    }

    public function resetOptionCreateForm()
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
    }

    public function storeOption()
    {
        $this->validate([
            'optionCreateText' => 'required|string',
            'optionCreateIsCorrect' => 'boolean',
        ]);

        QuizOption::create([
            'question_id' => $this->selectedSectionId,
            'option_text' => $this->optionCreateText,
            'is_correct' => $this->optionCreateIsCorrect,
        ]);

        $this->closeOptionModal();
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

        $this->closeOptionModal();
        Toaster::success('Option updated successfully!');
    }

    public function deleteOption()
    {
        if ($this->deletingOption) {
            $this->deletingOption->delete();
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

    public function getAttemptsProperty()
    {
        if (!$this->attemptsQuizId) {
            return collect();
        }

        return \App\Models\Tenant\QuizAttempt::where('quiz_id', $this->attemptsQuizId)
            ->with('user')
            ->orderBy('submitted_at', 'desc')
            ->get();
    }

    public function render()
    {
        $quizzes = Quiz::with([
            'section.course',
            'questions' => function ($query) {
                $query->with('options');
            }
        ])
            ->whereHas('section.course', function ($query) {
                $query->where('instructor_id', auth()->user()->id);
            })
            ->paginate(10);

        $sections = Section::with('course')->get();

        return view('livewire.instructor.quizzes', [
            'quizzes' => $quizzes,
            'sections' => $sections,
        ]);
    }
}