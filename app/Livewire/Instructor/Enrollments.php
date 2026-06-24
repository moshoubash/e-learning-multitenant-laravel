<?php

namespace App\Livewire\Instructor;

use App\Models\Tenant\Enrollment;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;

#[Layout('layouts.instructor')]
class Enrollments extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    #[Url(as: 'search')]
    public $search = '';

    #[Url(as: 'status')]
    public $statusFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Enrollment::with(['user', 'course'])
            ->whereHas('course', function ($q) {
                $q->where('instructor_id', auth()->id());
            });

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                })->orWhereHas('course', function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%');
                });
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $enrollments = $query->latest('enrolled_at')->paginate(10);
        $enrollments->withPath('/' . trim(\Livewire\Livewire::originalPath(), '/'));

        return view('livewire.instructor.enrollments', [
            'enrollments' => $enrollments,
        ]);
    }
}
