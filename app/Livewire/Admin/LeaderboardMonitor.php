<?php

namespace App\Livewire\Admin;

use App\Models\Tenant\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class LeaderboardMonitor extends Component
{
    use WithPagination;

    public string $search = '';

    protected $queryString = ['search'];

    public function render()
    {
        $query = User::whereHas('roles', function ($q) {
                $q->where('name', 'student');
            })
            ->where('total_points', '>', 0)
            ->orderByDesc('total_points');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        $students = $query->paginate(20);

        $stats = [
            'total' => User::whereHas('roles', fn($q) => $q->where('name', 'student'))
                ->where('total_points', '>', 0)
                ->count(),
            'average' => (int) User::whereHas('roles', fn($q) => $q->where('name', 'student'))
                ->where('total_points', '>', 0)
                ->avg('total_points'),
            'highest' => (int) User::whereHas('roles', fn($q) => $q->where('name', 'student'))
                ->max('total_points'),
        ];

        return view('livewire.admin.leaderboard-monitor', [
            'students' => $students,
            'stats' => $stats,
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
