<?php

namespace App\Console\Commands;

use App\Models\Tenant\User;
use App\Notifications\WeeklyReportReady;
use App\Services\Reports\ReportsService;
use Dompdf\Dompdf;
use Illuminate\Console\Command;

class GenerateWeeklyReport extends Command
{
    protected $signature = 'reports:weekly';
    protected $description = 'Generate and email the weekly analytics report to all admins';

    public function handle(): int
    {
        $service = app(ReportsService::class);
        $data = $service->weeklyReportData();

        $html = view('pdf.weekly-report', $data)->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdfContent = $dompdf->output();

        $admins = User::role('admin')->get();

        if ($admins->isEmpty()) {
            $this->warn('No admin users found to send the report.');
            return self::SUCCESS;
        }

        foreach ($admins as $admin) {
            $admin->notify(new WeeklyReportReady($pdfContent, $data));
        }

        $this->info('Weekly report sent to ' . $admins->count() . ' admin(s).');
        return self::SUCCESS;
    }
}
