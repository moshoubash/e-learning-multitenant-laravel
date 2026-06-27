<?php

namespace App\Services\Dashboard;

use App\Models\Tenant\AssignmentSubmission;
use App\Models\Tenant\Course;
use App\Models\Tenant\Enrollment;
use App\Models\Tenant\LessonProgress;
use App\Models\Tenant\Quiz;
use App\Models\Tenant\QuizAttempt;
use App\Models\Tenant\User;
use App\Services\DesignConfigService;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DashboardStatsService
{
    protected int $monthlyWindow = 6;
    protected int $cacheTtl = 300;

    protected function cacheKey(string $key): string
    {
        $tenantId = tenant('id') ?? 'central';
        return "dashboard:{$tenantId}:{$key}";
    }

    // -----------------------------------------------------------------
    // Admin
    // -----------------------------------------------------------------
    public function adminKpis(): array
    {
        return Cache::remember($this->cacheKey('admin:kpis'), $this->cacheTtl, function () {
            $now = CarbonImmutable::now();
        $lastMonth = $now->subMonth();
        $thisMonthStart = $now->startOfMonth();
        $lastMonthStart = $lastMonth->startOfMonth();

        $totalUsers = User::count();
        $newUsersThisMonth = User::where('created_at', '>=', $thisMonthStart)->count();
        $newUsersLastMonth = User::whereBetween('created_at', [$lastMonthStart, $thisMonthStart])->count();

        $totalCourses = Course::count();
        $newCoursesThisMonth = Course::where('created_at', '>=', $thisMonthStart)->count();
        $newCoursesLastMonth = Course::whereBetween('created_at', [$lastMonthStart, $thisMonthStart])->count();

        $totalEnrollments = Enrollment::count();
        $newEnrollmentsThisMonth = Enrollment::where('enrolled_at', '>=', $thisMonthStart)->count();
        $newEnrollmentsLastMonth = Enrollment::whereBetween('enrolled_at', [$lastMonthStart, $thisMonthStart])->count();

        $totalQuizzes = Quiz::count();
        $totalQuizAttempts = QuizAttempt::count();

        return [
            [
                'label' => __('messages.Total Users'),
                'value' => number_format($totalUsers),
                'icon' => 'fas fa-users',
                'color' => $this->dynamicColor('on_surface'),
                'change' => $this->percentChange($newUsersThisMonth, $newUsersLastMonth),
            ],
            [
                'label' => __('messages.Total Courses'),
                'value' => number_format($totalCourses),
                'icon' => 'fas fa-book-open',
                'color' => $this->dynamicColor('primary_container'),
                'change' => $this->percentChange($newCoursesThisMonth, $newCoursesLastMonth),
            ],
            [
                'label' => __('messages.Total Enrollments'),
                'value' => number_format($totalEnrollments),
                'icon' => 'fas fa-graduation-cap',
                'color' => $this->dynamicColor('on_primary_container'),
                'change' => $this->percentChange($newEnrollmentsThisMonth, $newEnrollmentsLastMonth),
            ],
            [
                'label' => __('messages.Quiz Attempts'),
                'value' => number_format($totalQuizAttempts),
                'icon' => 'fas fa-question-circle',
                'color' => $this->dynamicColor('secondary'),
                'change' => null,
                'sub' => number_format($totalQuizzes) . ' ' . __('messages.quizzes'),
            ],
        ];
        });
    }

    public function adminUserRoleChart(): array
    {
        return Cache::remember($this->cacheKey('admin:roleChart'), $this->cacheTtl, function () {
            $roles = Role::withCount('users')->get();

        $labels = [];
        $data = [];
        $palette = $this->dynamicChartColors();

        foreach ($roles as $index => $role) {
            $labels[] = ucfirst($role->name);
            $data[] = $role->users_count;
        }

        // Ensure there is at least one slice for the chart to render
        if (empty($labels)) {
            $labels = ['—'];
            $data = [0];
        }

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
        });
    }

    public function adminEnrollmentTrendChart(): array
    {
        return Cache::remember($this->cacheKey('admin:enrollmentTrend'), $this->cacheTtl, function () {
            return [
            'type' => 'line',
            'labels' => $this->monthLabels(),
            'datasets' => [
                [
                    'label' => __('messages.Enrollments'),
                    'data' => $this->monthlyCounts(Enrollment::class, 'enrolled_at'),
                    'color' => $this->dynamicColor('on_surface'),
                ],
                [
                    'label' => __('messages.New Users'),
                    'data' => $this->monthlyCounts(User::class, 'created_at'),
                    'color' => $this->dynamicColor('primary_container'),
                ],
            ],
        ];
        });
    }

    public function adminCourseStatusChart(): array
    {
        return Cache::remember($this->cacheKey('admin:courseStatus'), $this->cacheTtl, function () {
            $statuses = Course::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $labels = [];
        $data = [];
        $chartColors = $this->dynamicChartColors();
        $colors = [$chartColors[0] ?? '#FFD600', $chartColors[4] ?? '#705d00', $chartColors[5] ?? '#E2E2E2'];

        $order = ['published', 'draft', 'archived'];
        foreach ($order as $i => $status) {
            $labels[] = __(ucfirst($status === 'published' ? __('messages.published') : ($status === 'draft' ? __('messages.draft') : __('messages.archived'))));
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
        });
    }

    public function adminRecentEnrollments(int $limit = 5): Collection
    {
        return Cache::remember($this->cacheKey("admin:recentEnrollments:{$limit}"), $this->cacheTtl, function () use ($limit) {
            return Enrollment::with(['user', 'course.instructor'])
                ->orderByDesc('enrolled_at')
                ->limit($limit)
                ->get();
        });
    }

    public function adminTopCourses(int $limit = 5): Collection
    {
        return Cache::remember($this->cacheKey("admin:topCourses:{$limit}"), $this->cacheTtl, function () use ($limit) {
            return Course::withCount('enrollments')
                ->with('instructor')
                ->orderByDesc('enrollments_count')
                ->limit($limit)
                ->get();
        });
    }

    public function adminRecentUsers(int $limit = 5): Collection
    {
        return Cache::remember($this->cacheKey("admin:recentUsers:{$limit}"), $this->cacheTtl, function () use ($limit) {
            return User::with('roles')
                ->orderByDesc('created_at')
                ->limit($limit)
                ->get();
        });
    }

    // -----------------------------------------------------------------
    // Instructor
    // -----------------------------------------------------------------
    public function instructorKpis(int $instructorId): array
    {
        return Cache::remember($this->cacheKey("instructor:{$instructorId}:kpis"), $this->cacheTtl, function () use ($instructorId) {
            $courses = Course::where('instructor_id', $instructorId);
            $totalCourses = (clone $courses)->count();
            $publishedCourses = (clone $courses)->where('status', 'published')->count();

            $courseIds = (clone $courses)->pluck('id');

            $totalStudents = Enrollment::whereIn('course_id', $courseIds)->distinct('user_id')->count('user_id');
            $totalEnrollments = Enrollment::whereIn('course_id', $courseIds)->count();
            $totalQuizzes = Quiz::whereIn('section_id', function ($q) use ($courseIds) {
                $q->select('id')->from('sections')->whereIn('course_id', $courseIds);
            })->count();

            $pendingSubmissions = AssignmentSubmission::where('status', 'submitted')
                ->whereHas('assignment', function ($q) use ($courseIds) {
                    $q->whereIn('course_id', $courseIds);
                })
                ->count();

            return [
                [
                    'label' => __('messages.My Courses'),
                    'value' => number_format($totalCourses),
                    'icon' => 'fas fa-book-open',
                    'color' => $this->dynamicColor('on_surface'),
                    'sub' => number_format($publishedCourses) . ' ' . __('messages.published'),
                ],
                [
                    'label' => __('messages.My Students'),
                    'value' => number_format($totalStudents),
                    'icon' => 'fas fa-user-graduate',
                    'color' => $this->dynamicColor('primary_container'),
                    'sub' => number_format($totalEnrollments) . ' ' . __('messages.enrollments'),
                ],
                [
                    'label' => __('messages.My Quizzes'),
                    'value' => number_format($totalQuizzes),
                    'icon' => 'fas fa-question-circle',
                    'color' => $this->dynamicColor('on_primary_container'),
                ],
                [
                    'label' => __('messages.Pending Review'),
                    'value' => number_format($pendingSubmissions),
                    'icon' => 'fas fa-inbox',
                    'color' => $this->dynamicColor('secondary'),
                ],
            ];
        });
    }

    public function instructorStudentsPerCourseChart(int $instructorId): array
    {
        return Cache::remember($this->cacheKey("instructor:{$instructorId}:studentsPerCourse"), $this->cacheTtl, function () use ($instructorId) {
            $rows = Course::where('instructor_id', $instructorId)
                ->withCount('enrollments')
                ->orderByDesc('enrollments_count')
                ->limit(7)
                ->get();

            $labels = $rows->pluck('title')->map(fn ($t) => \Illuminate\Support\Str::limit($t, 18))->toArray();
            $data = $rows->pluck('enrollments_count')->map(fn ($v) => (int) $v)->toArray();

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
        });
    }

    public function instructorQuizAttemptsChart(int $instructorId): array
    {
        return Cache::remember($this->cacheKey("instructor:{$instructorId}:quizAttempts"), $this->cacheTtl, function () use ($instructorId) {
            $rows = QuizAttempt::query()
                ->selectRaw("DATE_FORMAT(submitted_at, '%Y-%m') as month, COUNT(*) as total")
                ->whereHas('quiz.section.course', function ($q) use ($instructorId) {
                    $q->where('instructor_id', $instructorId);
                })
                ->where('submitted_at', '>=', CarbonImmutable::now()->subMonths($this->monthlyWindow - 1)->startOfMonth())
                ->groupBy('month')
                ->pluck('total', 'month');

            $data = $this->shapeMonthlyData($rows);

            return [
                'type' => 'line',
                'labels' => $this->monthLabels(),
                'datasets' => [
                    [
                        'label' => __('messages.Quiz Attempts'),
                        'data' => $data,
                        'color' => $this->dynamicColor('secondary'),
                    ],
                ],
            ];
        });
    }

    public function instructorSubmissionStatusChart(int $instructorId): array
    {
        return Cache::remember($this->cacheKey("instructor:{$instructorId}:submissionStatus"), $this->cacheTtl, function () use ($instructorId) {
            $courseIds = Course::where('instructor_id', $instructorId)->pluck('id');

            $rows = AssignmentSubmission::query()
                ->select('status', DB::raw('count(*) as total'))
                ->whereHas('assignment', function ($q) use ($courseIds) {
                    $q->whereIn('course_id', $courseIds);
                })
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
        });
    }

    public function instructorRecentEnrollments(int $instructorId, int $limit = 5): Collection
    {
        return Cache::remember($this->cacheKey("instructor:{$instructorId}:recentEnrollments:{$limit}"), $this->cacheTtl, function () use ($instructorId, $limit) {
            $courseIds = Course::where('instructor_id', $instructorId)->pluck('id');

            return Enrollment::with(['user', 'course'])
                ->whereIn('course_id', $courseIds)
                ->orderByDesc('enrolled_at')
                ->limit($limit)
                ->get();
        });
    }

    public function instructorRecentSubmissions(int $instructorId, int $limit = 5): Collection
    {
        return Cache::remember($this->cacheKey("instructor:{$instructorId}:recentSubmissions:{$limit}"), $this->cacheTtl, function () use ($instructorId, $limit) {
            $courseIds = Course::where('instructor_id', $instructorId)->pluck('id');

            return AssignmentSubmission::with(['student', 'assignment.course'])
                ->whereHas('assignment', function ($q) use ($courseIds) {
                    $q->whereIn('course_id', $courseIds);
                })
                ->orderByDesc('submitted_at')
                ->limit($limit)
                ->get();
        });
    }

    public function instructorMyCourses(int $instructorId, int $limit = 5): Collection
    {
        return Cache::remember($this->cacheKey("instructor:{$instructorId}:myCourses:{$limit}"), $this->cacheTtl, function () use ($instructorId, $limit) {
            return Course::where('instructor_id', $instructorId)
                ->withCount(['enrollments', 'sections'])
                ->orderByDesc('updated_at')
                ->limit($limit)
                ->get();
        });
    }

    // -----------------------------------------------------------------
    // Student
    // -----------------------------------------------------------------
    public function studentKpis(int $userId): array
    {
        return Cache::remember($this->cacheKey("student:{$userId}:kpis"), $this->cacheTtl, function () use ($userId) {
            $enrollments = Enrollment::where('user_id', $userId)->get();
            $totalEnrolled = $enrollments->count();
            $inProgress = $enrollments->where('progress_percent', '>', 0)->whereNull('completed_at')->count();
            $completed = $enrollments->whereNotNull('completed_at')->count();

            $attempts = QuizAttempt::where('user_id', $userId)->get();
            $totalAttempts = $attempts->count();
            $passedAttempts = $attempts->where('passed', true)->count();
            $passRate = $totalAttempts > 0 ? (int) round(($passedAttempts / $totalAttempts) * 100) : 0;

            $lessonsCompleted = LessonProgress::where('user_id', $userId)
                ->where('is_completed', true)
                ->count();

            return [
                [
                    'label' => __('messages.Enrolled Courses'),
                    'value' => number_format($totalEnrolled),
                    'icon' => 'fas fa-graduation-cap',
                    'color' => $this->dynamicColor('on_surface'),
                    'sub' => number_format($inProgress) . ' ' . __('messages.In Progress'),
                ],
                [
                'label' => __('messages.Completed'),
                'value' => number_format($completed),
                'icon' => 'fas fa-check-circle',
                'color' => $this->dynamicColor('primary_container'),
                'sub' => number_format($lessonsCompleted) . ' ' . __('messages.lessons'),
            ],
            [
                'label' => __('messages.Quiz Attempts'),
                'value' => number_format($totalAttempts),
                'icon' => 'fas fa-question-circle',
                'color' => $this->dynamicColor('secondary'),
                'sub' => number_format($passedAttempts) . ' ' . __('messages.Passed'),
            ],
            [
                'label' => __('messages.Pass Rate'),
                'value' => $passRate . '%',
                'icon' => 'fas fa-chart-line',
                'color' => $this->dynamicColor('on_primary_container'),
                'progress' => $passRate,
            ],
        ];
        });
    }

    public function studentProgressChart(int $userId): array
    {
        return Cache::remember($this->cacheKey("student:{$userId}:progressChart"), $this->cacheTtl, function () use ($userId) {
            $start = CarbonImmutable::now()->subWeeks(6)->startOfWeek();

            $rows = LessonProgress::where('user_id', $userId)
                ->where('is_completed', true)
                ->where('last_watched_at', '>=', $start)
                ->get()
                ->groupBy(fn ($item) => $item->last_watched_at->startOfWeek()->format('Y-m-d'));

            $labels = [];
            $data = [];
            for ($i = 0; $i < 7; $i++) {
                $day = $start->addWeeks($i);
                $key = $day->format('Y-m-d');
                $labels[] = $day->format('M d');
                $data[] = isset($rows[$key]) ? $rows[$key]->count() : 0;
            }

            return [
                'type' => 'line',
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => __('messages.Lessons Completed'),
                        'data' => $data,
                        'color' => $this->dynamicColor('on_surface'),
                    ],
                ],
            ];
        });
    }

    public function studentQuizScoreChart(int $userId): array
    {
        return Cache::remember($this->cacheKey("student:{$userId}:quizScoreChart"), $this->cacheTtl, function () use ($userId) {
            $attempts = QuizAttempt::where('user_id', $userId)
                ->with('quiz')
                ->orderBy('submitted_at')
                ->limit(4)
                ->get();

            $labels = $attempts->map(fn ($a) => \Illuminate\Support\Str::limit(optional($a->quiz)->title ?? '—', 26))->toArray();
            $data = $attempts->pluck('score')->map(fn ($v) => (int) $v)->toArray();

            if (empty($labels)) {
                $labels = ['—'];
                $data = [0];
            }

            return [
                'type' => 'bar',
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => __('messages.Score'),
                        'data' => $data,
                        'color' => $this->dynamicColor('primary_container'),
                    ],
                ],
            ];
        });
    }

    public function studentEnrollmentProgressTable(int $userId, int $limit = 5): Collection
    {
        return Cache::remember($this->cacheKey("student:{$userId}:enrollments:{$limit}"), $this->cacheTtl, function () use ($userId, $limit) {
            return Enrollment::with(['course.instructor'])
                ->where('user_id', $userId)
                ->whereHas('course')
                ->orderByDesc('enrolled_at')
                ->limit($limit)
                ->get();
        });
    }

    public function studentRecentAttempts(int $userId, int $limit = 5): Collection
    {
        return Cache::remember($this->cacheKey("student:{$userId}:recentAttempts:{$limit}"), $this->cacheTtl, function () use ($userId, $limit) {
            return QuizAttempt::with(['quiz.section.course'])
                ->where('user_id', $userId)
                ->orderByDesc('submitted_at')
                ->limit($limit)
                ->get();
        });
    }

    // -----------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------
    protected function monthLabels(): array
    {
        $labels = [];
        $now = CarbonImmutable::now();
        for ($i = $this->monthlyWindow - 1; $i >= 0; $i--) {
            $labels[] = $now->subMonths($i)->format('M');
        }
        return $labels;
    }

    protected function monthlyCounts(string $model, string $dateColumn): array
    {
        $now = CarbonImmutable::now();
        $start = $now->subMonths($this->monthlyWindow - 1)->startOfMonth();

        $rows = $model::query()
            ->selectRaw("DATE_FORMAT($dateColumn, '%Y-%m') as month, COUNT(*) as total")
            ->where($dateColumn, '>=', $start)
            ->groupBy('month')
            ->pluck('total', 'month');

        return $this->shapeMonthlyData($rows);
    }

    protected function shapeMonthlyData(Collection $rows): array
    {
        $data = [];
        $now = CarbonImmutable::now();
        for ($i = $this->monthlyWindow - 1; $i >= 0; $i--) {
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
        if ($previous === 0 && $current === 0) {
            return 0;
        }
        if ($previous === 0) {
            return 100;
        }
        return (int) round((($current - $previous) / $previous) * 100);
    }
}
