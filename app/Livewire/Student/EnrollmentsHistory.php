<?php

namespace App\Livewire\Student;

use App\Models\Tenant\Enrollment;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;

#[Layout('layouts.student')]
class EnrollmentsHistory extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    #[Url(as: 'status')]
    public $statusFilter = '';

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Enrollment::with(['course' => function ($q) {
                $q->withTrashed()->with('instructor');
            }])
            ->where('user_id', auth()->id());

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $enrollments = $query->latest('enrolled_at')->paginate(10);
        $enrollments->withPath('/' . trim(\Livewire\Livewire::originalPath(), '/'));

        return view('livewire.student.enrollments-history', [
            'enrollments' => $enrollments,
        ]);
    }
}
