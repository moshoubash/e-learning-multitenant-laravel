<?php

namespace App\Livewire\Student;

use App\Models\Tenant\Enrollment;
use App\Models\Tenant\LessonProgress;
use App\Services\Student\EnrolledCoursesService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.student')]
class EnrolledCourses extends Component
{
    public function getEnrolledCourses()
    {
        return $this->enrolledCoursesService()->getEnrolledCourses(auth()->id());
    }

    public function getCourseProgress($enrollment)
    {
        return $this->enrolledCoursesService()->getCourseProgress($enrollment, auth()->id());
    }

    public function render()
    {
        $enrollments = $this->getEnrolledCourses();

        return view('livewire.student.enrolled-courses', [
            'enrollments' => $enrollments,
        ]);
    }

    protected function enrolledCoursesService(): EnrolledCoursesService
    {
        return new EnrolledCoursesService();
    }
}
