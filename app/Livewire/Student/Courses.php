<?php

namespace App\Livewire\Student;

use App\Models\Tenant\Course;
use App\Models\Tenant\Enrollment;
use Livewire\Component;

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
        return Course::with([
            'instructor',
            'sections' => function ($query) {
                $query->with([
                    'lessons',
                    'quiz' => function ($q) {
                        $q->with('questions.options');
                    }
                ])->orderBy('order');
            }
        ])
            ->where('status', 'published')
            ->orderBy('title')
            ->get();
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
            $selectedCourseData = Course::with([
                'instructor',
                'sections' => function ($query) {
                    $query->with([
                        'lessons',
                        'quiz' => function ($quizQuery) {
                            $quizQuery->with('questions.options');
                        }
                    ])->orderBy('order');
                }
            ])->find($this->selectedCourse);
        }

        return view('livewire.student.courses', [
            'courses' => $courses,
            'selectedCourseData' => $selectedCourseData,
        ]);
    }
}