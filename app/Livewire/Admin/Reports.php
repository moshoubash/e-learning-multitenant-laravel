<?php

namespace App\Livewire\Admin;

use App\Services\Reports\ReportsService;
use Livewire\Component;

class Reports extends Component
{
    public string $activeTab = 'overview';
    public int $period = 30;
    public array $kpis = [];
    public array $charts = [];
    public array $tables = [];

    protected array $periods = [7, 30, 90, 365];

    public function mount()
    {
        $this->loadData();
    }

    public function switchTab(string $tab)
    {
        $this->activeTab = $tab;
        $this->loadData();
    }

    public function setPeriod(int $period)
    {
        if (in_array($period, $this->periods)) {
            $this->period = $period;
            $this->loadData();
        }
    }

    public function exportCsv()
    {
        $csv = app(ReportsService::class)->exportOverviewCsv($this->period);
        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'report-overview.csv', ['Content-Type' => 'text/csv']);
    }

    protected function loadData()
    {
        $service = app(ReportsService::class);

        switch ($this->activeTab) {
            case 'overview':
                $this->kpis = $service->overviewKpis($this->period);
                $this->charts = [
                    'enrollmentTrend' => $service->enrollmentTrend(),
                    'courseStatus' => $service->courseStatus(),
                ];
                $this->tables = [];
                break;

            case 'users':
                $this->kpis = [];
                $this->charts = [
                    'usersByRole' => $service->usersByRole(),
                    'userTrend' => [
                        'type' => 'line',
                        'labels' => $this->monthLabels(),
                        'datasets' => [
                            [
                                'label' => __('messages.New Users'),
                                'data' => $this->monthlyCounts(\App\Models\Tenant\User::class, 'created_at'),
                                'color' => $this->dynamicColor('on_surface'),
                            ],
                        ],
                    ],
                ];
                $this->tables = [
                    'topStudents' => $service->topStudents(10),
                ];
                break;

            case 'courses':
                $this->kpis = [];
                $this->charts = [
                    'courseEnrollments' => $service->courseEnrollments(10),
                    'completionRates' => $service->completionRates(10),
                ];
                $this->tables = [];
                break;

            case 'quizzes':
                $this->kpis = [];
                $this->charts = [
                    'quizAttemptsTrend' => $service->quizAttemptsTrend(),
                ];
                $this->tables = [
                    'quizPerformance' => $service->quizPerformanceTable(10),
                ];
                break;

            case 'assignments':
                $this->kpis = [];
                $this->charts = [
                    'submissionStatus' => $service->submissionStatus(),
                ];
                $this->tables = [];
                break;
        }
    }

    public function render()
    {
        return view('livewire.admin.reports')
            ->layout('layouts.admin');
    }

    protected function monthLabels(int $months = 6): array
    {
        $labels = [];
        $now = \Carbon\CarbonImmutable::now();
        for ($i = $months - 1; $i >= 0; $i--) {
            $labels[] = $now->subMonths($i)->format('M');
        }
        return $labels;
    }

    protected function monthlyCounts(string $model, string $dateColumn, int $months = 6): array
    {
        $now = \Carbon\CarbonImmutable::now();
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

    protected function dynamicColor(string $key): string
    {
        return app(\App\Services\DesignConfigService::class)->getColor($key);
    }
}
