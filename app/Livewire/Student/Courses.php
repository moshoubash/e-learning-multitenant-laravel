<?php

namespace App\Livewire\Student;

use App\Models\Tenant\Course;
use App\Models\Tenant\Enrollment;
use App\Services\Student\CoursesService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

#[Layout('layouts.student')]
class Courses extends Component
{
    public $selectedCourse = null;
    public $expandedSections = [];

    public function mount()
    {
        $courses = $this->getCourses();
        if ($courses->isNotEmpty()) {
            $this->selectedCourse = $courses->first()->id;
        }
    }

    public function getCourses()
    {
        return $this->coursesService()->getCourses();
    }

    public function isEnrolled($courseId)
    {
        return $this->coursesService()->isEnrolled($courseId, auth()->id());
    }

    public function enrollInCourse($courseId)
    {
        $course = Course::find($courseId);
        if ($course && $course->price == 0) {
            $enrollment = $this->coursesService()->enrollInCourse($courseId, auth()->id());

            Toaster::success('Successfully enrolled in the course!');
            return redirect()->route('tenant.student.checkout.success', ['enrollmentId' => $enrollment->id]);
        }

        return redirect()->route('tenant.student.checkout', ['course' => $courseId]);
    }

    public function selectCourse($courseId)
    {
        $this->selectedCourse = $courseId;
        $this->expandedSections = [];
    }

    public function toggleSection($sectionId)
    {
        if (in_array($sectionId, $this->expandedSections)) {
            $this->expandedSections = array_filter($this->expandedSections, fn($id) => $id !== $sectionId);
        } else {
            $this->expandedSections[] = $sectionId;
        }
    }

    public function isSectionExpanded($sectionId)
    {
        return in_array($sectionId, $this->expandedSections);
    }

    public function render()
    {
        $courses = $this->getCourses();
        $selectedCourseData = null;

        if ($this->selectedCourse) {
            $selectedCourseData = $this->coursesService()->getCourseById($this->selectedCourse);
        }

        return view('livewire.student.courses', [
            'courses' => $courses,
            'selectedCourseData' => $selectedCourseData,
        ]);
    }

    protected function coursesService(): CoursesService
    {
        return new CoursesService();
    }
}
