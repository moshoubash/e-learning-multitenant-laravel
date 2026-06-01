<?php

namespace App\Livewire\Student;

use App\Models\Tenant\Enrollment;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.student')]
class CheckoutSuccess extends Component
{
    public ?Enrollment $enrollment = null;
    public bool $isLoaded = false;

    public function mount(?int $enrollmentId = null)
    {
        if ($enrollmentId) {
            $this->enrollment = Enrollment::with(['course.instructor', 'user'])
                ->where('id', $enrollmentId)
                ->where('user_id', Auth::id())
                ->first();

            if ($this->enrollment) {
                $this->isLoaded = true;
            }
        }
    }

    public function render()
    {
        return view('livewire.student.checkout-success');
    }
}
