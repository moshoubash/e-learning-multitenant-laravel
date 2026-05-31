<?php

namespace App\Livewire;

use Livewire\Component;

class Profile extends Component
{
    public function render()
    {
        $user = auth()->user();
        if ($user && $user->hasRole('instructor')) {
            return view('profile')->layout('layouts.instructor');
        }
        return view('profile')->layout('layouts.student');
    }
}
