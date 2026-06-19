<?php

namespace App\Livewire\Instructor;

use App\Models\Tenant\Assignment;
use App\Models\Tenant\AssignmentSubmission;
use App\Notifications\AssignmentGraded;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

#[Layout('layouts.instructor')]
class AssignmentSubmissions extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public ?int $gradingSubmissionId = null;
    public bool $showGradingModal = false;

    public float $gradeScore = 0;
    public string $gradeFeedback = '';
    public string $filterStatus = 'all';

    public function mount(): void
    {
        $this->filterStatus = request()->get('status', 'all');
    }

    #[Computed]
    public function gradingSubmission(): ?AssignmentSubmission
    {
        if (! $this->gradingSubmissionId) {
            return null;
        }

        return AssignmentSubmission::with(['student', 'assignment', 'gradedBy'])
            ->find($this->gradingSubmissionId);
    }

    #[Computed]
    public function submissions()
    {
        $query = AssignmentSubmission::with(['student', 'assignment', 'gradedBy'])
            ->whereHas('assignment', fn ($q) => $q->where('created_by', Auth::id()));

        match ($this->filterStatus) {
            'pending' => $query->whereNull('graded_at'),
            'graded'  => $query->whereNotNull('graded_at'),
            default   => null,
        };

        return $query->latest('submitted_at')->paginate(15);
    }

    #[Computed]
    public function assignments()
    {
        return Assignment::where('created_by', Auth::id())
            ->withCount('submissions')
            ->withCount(['submissions as pending_count' => fn ($q) => $q->whereNull('graded_at')])
            ->get();
    }

    public function openGradingModal(int $submissionId): void
    {
        $submission = AssignmentSubmission::find($submissionId);

        if (! $submission) {
            Toaster::error('Submission not found');
            return;
        }

        $this->gradingSubmissionId = $submissionId;
        $this->gradeScore          = $submission->score ?? 0;
        $this->gradeFeedback       = $submission->feedback ?? '';
        $this->showGradingModal    = true;
    }

    public function closeGradingModal(): void
    {
        $this->showGradingModal    = false;
        $this->gradingSubmissionId = null;
        $this->gradeScore          = 0;
        $this->gradeFeedback       = '';
    }

    public function submitGrade(): void
    {
        $this->validate([
            'gradeScore' => 'required|numeric|min:0',
        ]);

        $submission = $this->gradingSubmission;

        if (! $submission) {
            return;
        }

        $maxScore = $submission->assignment->max_score ?? 100;

        if ($this->gradeScore > $maxScore) {
            Toaster::error("Score cannot exceed maximum score of {$maxScore}");
            return;
        }

        $submission->update([
            'score'      => $this->gradeScore,
            'feedback'   => $this->gradeFeedback,
            'graded_by'  => Auth::id(),
            'graded_at'  => now(),
            'status'     => 'graded',
        ]);

        if ($submission->student) {
            $submission->student->notify(new AssignmentGraded(
                $submission->assignment,
                $this->gradeScore,
                $this->gradeFeedback,
            ));
        }

        Toaster::success('Grade submitted successfully!');
        $this->closeGradingModal();
    }

    public function setFilterStatus(string $status): void
    {
        $this->filterStatus = $status;
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.instructor.assignment-submissions');
    }
}
