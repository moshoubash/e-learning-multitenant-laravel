<?php

namespace App\Livewire\Student;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.student')]
class Leaderboard extends Component
{
    public function render()
    {
        return view('livewire.student.leaderboard');
    }
}
