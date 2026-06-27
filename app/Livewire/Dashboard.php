<?php

namespace App\Livewire;

use App\Services\Dashboard\DashboardStatsService;
use Livewire\Component;

class Dashboard extends Component
{
    public string $role = 'student';
    public array $kpis = [];
    public array $charts = [];
    public array $tables = [];

    public function mount()
    {
        $user = auth()->user();
        $this->role = $this->resolveRole($user);
    }

    public function render()
    {
        $stats = app(DashboardStatsService::class);
        $userId = auth()->id();

        if ($this->role === 'admin') {
            $this->kpis = $stats->adminKpis();
            $this->charts = [
                'userRoles' => $stats->adminUserRoleChart(),
                'enrollmentTrend' => $stats->adminEnrollmentTrendChart(),
                'courseStatus' => $stats->adminCourseStatusChart(),
            ];
            $this->tables = [
                'recentEnrollments' => $stats->adminRecentEnrollments(5),
                'topCourses' => $stats->adminTopCourses(5),
                'recentUsers' => $stats->adminRecentUsers(5),
            ];
            return view('dashboard')->layout('layouts.admin');
        }

        if ($this->role === 'instructor') {
            $this->kpis = $stats->instructorKpis($userId);
            $this->charts = [
                'studentsPerCourse' => $stats->instructorStudentsPerCourseChart($userId),
                'quizAttempts' => $stats->instructorQuizAttemptsChart($userId),
                'submissionStatus' => $stats->instructorSubmissionStatusChart($userId),
            ];
            $this->tables = [
                'recentEnrollments' => $stats->instructorRecentEnrollments($userId, 5),
                'recentSubmissions' => $stats->instructorRecentSubmissions($userId, 5),
                'myCourses' => $stats->instructorMyCourses($userId, 5),
            ];
            return view('dashboard')->layout('layouts.instructor');
        }

        $this->kpis = $stats->studentKpis($userId);
        $this->charts = [
            'progress' => $stats->studentProgressChart($userId),
            'quizScores' => $stats->studentQuizScoreChart($userId),
        ];
        $this->tables = [
            'enrollments' => $stats->studentEnrollmentProgressTable($userId, 5),
            'attempts' => $stats->studentRecentAttempts($userId, 4),
        ];
        return view('dashboard')->layout('layouts.student');
    }

    protected function resolveRole($user): string
    {
        if (! $user) {
            return 'student';
        }
        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole('admin')) return 'admin';
            if ($user->hasRole('instructor')) return 'instructor';
        }
        return 'student';
    }
}
