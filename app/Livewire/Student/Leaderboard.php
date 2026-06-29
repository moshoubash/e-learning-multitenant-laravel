<?php

namespace App\Livewire\Student;

use App\Services\Student\PointsService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.student')]
class Leaderboard extends Component
{
    public function render(PointsService $pointsService)
    {
        $userId = auth()->id();

        $leaders = $pointsService->getLeaderboard(20);
        $userRank = $pointsService->getUserRank($userId);
        $userPoints = $pointsService->getUserTotalPoints($userId);

        return view('livewire.student.leaderboard', [
            'leaders' => $leaders,
            'userRank' => $userRank,
            'userPoints' => $userPoints,
        ]);
    }
}
