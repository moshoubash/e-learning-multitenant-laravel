<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Models\Tenant\Course;
use App\Models\Tenant\Enrollment;
use Livewire\Component;

class Certificate extends Component
{
    public $course;
    public $enrollment;
    public $user;
    public $certificateId;
    public $completedAt;
    public $instructor;

    public function mount(Course $course)
    {
        $this->course = $course;

        $this->enrollment = Enrollment::where('course_id', $course->id)
            ->where('user_id', auth()->id())
            ->where('status', Enrollment::STATUS_COMPLETED)
            ->firstOrFail();

        $this->user = auth()->user();

        $this->certificateId = 'CERT-' . str_pad((string) $course->id, 4, '0', STR_PAD_LEFT)
            . '-' . str_pad((string) $this->enrollment->id, 4, '0', STR_PAD_LEFT);

        $this->completedAt = $this->enrollment->completed_at
            ? $this->enrollment->completed_at->format('F d, Y')
            : now()->format('F d, Y');

        $this->instructor = $course->instructor;
    }

    public function render()
    {
        return view('livewire.student.certificate');
    }
}
