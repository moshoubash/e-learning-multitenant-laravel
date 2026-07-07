<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class Updates extends Component
{
    public function render()
    {
        return view('livewire.updates');
    }
}
