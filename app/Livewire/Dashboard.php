<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        if ($user && $user->hasRole('admin')) {
            return view('dashboard')->layout('layouts.admin');
        }
        if ($user && $user->hasRole('instructor')) {
            return view('dashboard')->layout('layouts.instructor');
        }
        return view('dashboard')->layout('layouts.student');
    }
}
