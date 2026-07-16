<?php

namespace App\Services\Reports;

use App\Models\Tenant\Course;
use App\Models\Tenant\Enrollment;
use App\Models\Tenant\LessonProgress;
use App\Models\Tenant\QuizAttempt;
use App\Models\Tenant\AssignmentSubmission;
use App\Models\Tenant\User;
use App\Models\Tenant\PointsTransaction;
use App\Services\DesignConfigService;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class ReportsService
{
    public function overviewKpis(int $periodDays = 30): array
    {
        $now = CarbonImmutable::now();
        $currentStart = $now->subDays($periodDays);
        $previousStart = $currentStart->subDays($periodDays);

        $totalUsers = User::count();
        $newUsersCurrent = User::where('created_at', '>=', $currentStart)->count();
        $newUsersPrevious = User::whereBetween('created_at', [$previousStart, $currentStart])->count();

        $totalCourses = Course::count();
        $newCoursesCurrent = Course::where('created_at', '>=', $currentStart)->count();
        $newCoursesPrevious = Course::whereBetween('created_at', [$previousStart, $currentStart])->count();

        $totalEnrollments = Enrollment::count();
        $newEnrollmentsCurrent = Enrollment::where('enrolled_at', '>=', $currentStart)->count();
        $newEnrollmentsPrevious = Enrollment::whereBetween('enrolled_at', [$previousStart, $currentStart])->count();

        $totalQuizAttempts = QuizAttempt::count();
        $quizAttemptsCurrent = QuizAttempt::where('submitted_at', '>=', $currentStart)->count();
        $quizAttemptsPrevious = QuizAttempt::whereBetween('submitted_at', [$previousStart, $currentStart])->count();

        return [
            [
                'label' => __('messages.Total Users'),
                'value' => number_format($totalUsers),
                'icon' => 'fa-users',
                'color' => $this->dynamicColor('on_surface'),
                'change' => $this->percentChange($newUsersCurrent, $newUsersPrevious),
            ],
            [
                'label' => __('messages.Total Courses'),
                'value' => number_format($totalCourses),
                'icon' => 'fa-book-open',
                'color' => $this->dynamicColor('primary_container'),
                'change' => $this->percentChange($newCoursesCurrent, $newCoursesPrevious),
            ],
            [
                'label' => __('messages.Total Enrollments'),
                'value' => number_format($totalEnrollments),
                'icon' => 'fa-graduation-cap',
                'color' => $this->dynamicColor('on_primary_container'),
                'change' => $this->percentChange($newEnrollmentsCurrent, $newEnrollmentsPrevious),
            ],
            [
                'label' => __('messages.Quiz Attempts'),
                'value' => number_format($totalQuizAttempts),
                'icon' => 'fa-question-circle',
                'color' => $this->dynamicColor('secondary'),
                'change' => $this->percentChange($quizAttemptsCurrent, $quizAttemptsPrevious),
            ],
        ];
    }

    public function enrollmentTrend(int $months = 6): array
    {
        return [
            'type' => 'line',
            'labels' => $this->monthLabels($months),
            'datasets' => [
                [
                    'label' => __('messages.Enrollments'),
                    'data' => $this->monthlyCounts(Enrollment::class, 'enrolled_at', $months),
                    'color' => $this->dynamicColor('on_surface'),
                ],
                [
                    'label' => __('messages.New Users'),
                    'data' => $this->monthlyCounts(User::class, 'created_at', $months),
                    'color' => $this->dynamicColor('primary_container'),
                ],
            ],
        ];
    }

    public function usersByRole(): array
    {
        $roles = Role::withCount('users')->get();

        $labels = [];
        $data = [];

        foreach ($roles as $role) {
            $labels[] = ucfirst($role->name);
            $data[] = $role->users_count;
        }

        if (empty($labels)) {
            $labels = ['—'];
            $data = [0];
        }

        $palette = $this->dynamicChartColors();

        return [
            'type' => 'doughnut',
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => __('messages.Users'),
                    'data' => $data,
                    'backgroundColor' => array_slice($palette, 0, max(count($data), 1)),
                ],
            ],
        ];
    }

    public function courseEnrollments(int $limit = 10): array
    {
        $courses = Course::withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->limit($limit)
            ->get();

        $labels = $courses->pluck('title')->map(fn ($t) => \Illuminate\Support\Str::limit($t, 24))->toArray();
        $data = $courses->pluck('enrollments_count')->map(fn ($v) => (int) $v)->toArray();

        if (empty($labels)) {
            $labels = ['—'];
            $data = [0];
        }

        return [
            'type' => 'bar',
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => __('messages.Students'),
                    'data' => $data,
                    'color' => $this->dynamicColor('on_surface'),
                ],
            ],
        ];
    }

    public function courseStatus(): array
    {
        $statuses = Course::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $labels = [];
        $data = [];
        $chartColors = $this->dynamicChartColors();
        $colors = [$chartColors[0] ?? '#FFD600', $chartColors[4] ?? '#705d00', $chartColors[5] ?? '#E2E2E2'];

        $order = ['published', 'draft', 'archived'];
        foreach ($order as $i => $status) {
            $labels[] = __(ucfirst($status));
            $data[] = (int) ($statuses[$status] ?? 0);
        }

        return [
            'type' => 'bar',
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => __('messages.Courses'),
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                ],
            ],
        ];
    }

    public function completionRates(int $limit = 10): array
    {
        $courses = Course::withCount('enrollments')
            ->withCount(['enrollments as completed_count' => fn ($q) => $q->whereNotNull('completed_at')])
            ->having('enrollments_count', '>', 0)
            ->orderByDesc('enrollments_count')
            ->limit($limit)
            ->get();

        $labels = $courses->pluck('title')->map(fn ($t) => \Illuminate\Support\Str::limit($t, 24))->toArray();
        $data = $courses->map(fn ($c) => $c->enrollments_count > 0 ? (int) round(($c->completed_count / $c->enrollments_count) * 100) : 0)->toArray();

        if (empty($labels)) {
            $labels = ['—'];
            $data = [0];
        }

        return [
            'type' => 'bar',
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => __('messages.Completion Rate (%)'),
                    'data' => $data,
                    'color' => $this->dynamicColor('primary_container'),
                ],
            ],
        ];
    }

    public function quizAttemptsTrend(int $months = 6): array
    {
        return [
            'type' => 'line',
            'labels' => $this->monthLabels($months),
            'datasets' => [
                [
                    'label' => __('messages.Quiz Attempts'),
                    'data' => $this->monthlyCounts(QuizAttempt::class, 'submitted_at', $months),
                    'color' => $this->dynamicColor('secondary'),
                ],
            ],
        ];
    }

    public function quizPerformanceTable(int $limit = 10): Collection
    {
        return QuizAttempt::select(
            'quiz_id',
            DB::raw('COUNT(*) as total_attempts'),
            DB::raw('SUM(CASE WHEN passed = 1 THEN 1 ELSE 0 END) as passed_attempts'),
            DB::raw('AVG(score) as avg_score')
        )
            ->with('quiz.section.course')
            ->groupBy('quiz_id')
            ->having('total_attempts', '>', 0)
            ->orderByDesc('total_attempts')
            ->limit($limit)
            ->get()
            ->map(fn ($row) => [
                'quiz' => optional($row->quiz)->title ?? '—',
                'course' => optional(optional($row->quiz)->section)->course?->title ?? '—',
                'attempts' => $row->total_attempts,
                'passed' => $row->passed_attempts,
                'pass_rate' => $row->total_attempts > 0 ? (int) round(($row->passed_attempts / $row->total_attempts) * 100) : 0,
                'avg_score' => (int) round($row->avg_score),
            ]);
    }

    public function submissionStatus(): array
    {
        $rows = AssignmentSubmission::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $labels = [
            __('messages.Graded'),
            __('messages.Pending Review'),
        ];
        $data = [
            (int) ($rows['graded'] ?? 0),
            (int) ($rows['submitted'] ?? 0),
        ];

        return [
            'type' => 'doughnut',
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => __('messages.Submissions'),
                    'data' => $data,
                    'backgroundColor' => [$this->dynamicColor('primary_container'), $this->dynamicColor('secondary')],
                ],
            ],
        ];
    }

    public function topStudents(int $limit = 10): Collection
    {
        return User::whereHas('roles', fn ($q) => $q->where('name', 'student'))
            ->withSum('pointsTransactions as total_points', 'points')
            ->orderByDesc('total_points')
            ->limit($limit)
            ->get()
            ->map(fn ($user, $index) => [
                'rank' => $index + 1,
                'name' => $user->name,
                'email' => $user->email,
                'points' => (int) ($user->total_points ?? 0),
            ]);
    }

    public function weeklyReportData(): array
    {
        $now = CarbonImmutable::now();
        $weekAgo = $now->subWeek();

        return [
            'generated_at' => $now->toDateTimeString(),
            'period_start' => $weekAgo->toDateTimeString(),
            'period_end' => $now->toDateTimeString(),

            'total_users' => User::count(),
            'new_users_week' => User::where('created_at', '>=', $weekAgo)->count(),

            'total_courses' => Course::count(),
            'new_courses_week' => Course::where('created_at', '>=', $weekAgo)->count(),

            'total_enrollments' => Enrollment::count(),
            'new_enrollments_week' => Enrollment::where('enrolled_at', '>=', $weekAgo)->count(),

            'total_quiz_attempts' => QuizAttempt::count(),
            'quiz_attempts_week' => QuizAttempt::where('submitted_at', '>=', $weekAgo)->count(),

            'pending_submissions' => AssignmentSubmission::where('status', 'submitted')->count(),

            'top_courses' => Course::withCount('enrollments')
                ->orderByDesc('enrollments_count')
                ->limit(5)
                ->get()
                ->toArray(),

            'top_students' => User::whereHas('roles', fn ($q) => $q->where('name', 'student'))
                ->withSum('pointsTransactions as total_points', 'points')
                ->orderByDesc('total_points')
                ->limit(5)
                ->get()
                ->toArray(),
        ];
    }

    public function exportOverviewCsv(int $periodDays = 30): string
    {
        $kpis = $this->overviewKpis($periodDays);
        $lines = [];
        $lines[] = 'Metric,Value,Change (%)';
        foreach ($kpis as $kpi) {
            $lines[] = '"' . $kpi['label'] . '","' . $kpi['value'] . '","' . ($kpi['change'] ?? 'N/A') . '"';
        }
        $lines[] = '';
        $lines[] = 'Enrollment Trend (Last 6 Months)';
        $lines[] = 'Month,Count';
        $trend = $this->enrollmentTrend();
        foreach ($trend['labels'] as $i => $label) {
            $lines[] = '"' . $label . '","' . ($trend['datasets'][0]['data'][$i] ?? 0) . '"';
        }
        return implode("\n", $lines);
    }

    protected function monthLabels(int $months): array
    {
        $labels = [];
        $now = CarbonImmutable::now();
        for ($i = $months - 1; $i >= 0; $i--) {
            $labels[] = $now->subMonths($i)->format('M');
        }
        return $labels;
    }

    protected function monthlyCounts(string $model, string $dateColumn, int $months): array
    {
        $now = CarbonImmutable::now();
        $start = $now->subMonths($months - 1)->startOfMonth();

        $rows = $model::query()
            ->selectRaw("DATE_FORMAT($dateColumn, '%Y-%m') as month, COUNT(*) as total")
            ->where($dateColumn, '>=', $start)
            ->groupBy('month')
            ->pluck('total', 'month');

        $data = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $key = $now->subMonths($i)->format('Y-m');
            $data[] = (int) ($rows[$key] ?? 0);
        }
        return $data;
    }

    protected function designConfig(): DesignConfigService
    {
        return app(DesignConfigService::class);
    }

    protected function dynamicColor(string $key): string
    {
        return $this->designConfig()->getColor($key);
    }

    protected function dynamicChartColors(): array
    {
        return $this->designConfig()->getChartColors();
    }

    protected function percentChange(int $current, int $previous): ?int
    {
        if ($previous === 0 && $current === 0) return 0;
        if ($previous === 0) return 100;
        return (int) round((($current - $previous) / $previous) * 100);
    }
}
