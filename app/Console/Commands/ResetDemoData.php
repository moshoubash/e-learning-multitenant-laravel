<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Models\Tenant\Course;
use App\Models\Tenant\Lesson;
use App\Models\Tenant\Section;
use App\Models\Tenant\User;
use App\Models\Tenant\Enrollment;
use App\Models\Tenant\Quiz;
use App\Models\Tenant\QuizAttempt;
use App\Models\Tenant\QuizQuestion;
use App\Models\Tenant\QuizOption;
use App\Models\Tenant\Assignment;
use App\Models\Tenant\AssignmentSubmission;
use App\Models\Tenant\AssignmentGrade;
use App\Models\Tenant\AssignmentAttachment;
use App\Models\Tenant\LessonProgress;
use App\Models\Tenant\PointsTransaction;
use App\Models\Tenant\ContactMessage;
use App\Models\Tenant\DesignConfig;
use App\Models\Tenant\Integration;
use App\Models\Tenant\SmtpSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetDemoData extends Command
{
    protected $signature = 'app:reset-demo
        {--since= : Delete data created from this date onwards (Y-m-d). Defaults to start of today.}
        {--preserve-users : Keep user accounts, only delete their activity data}
        {--force : Skip confirmation prompt}';

    protected $description = 'Reset demo data by deleting everything created from the specified date onwards';

    public function handle(): int
    {
        $since = $this->option('since')
            ? now()->parse($this->option('since'))->startOfDay()
            : now()->startOfDay();

        $this->info("Reset cutoff: {$since->toDateTimeString()}");

        $tenants = Tenant::all();

        if ($tenants->isEmpty()) {
            $this->warn('No tenants found.');
            return 0;
        }

        // Summarize what will be deleted
        $totalCounts = [];
        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);
            $counts = $this->countRecords($since, $this->option('preserve-users'));
            $totalCounts[$tenant->name] = $counts;
            tenancy()->end();
        }

        $this->table(
            ['Tenant', 'Users', 'Enrollments', 'Courses', 'Sections', 'Lessons', 'Quizzes', 'Attempts', 'Assignments', 'Submissions', 'Progress', 'Points', 'Messages', 'Notifs'],
            collect($totalCounts)->map(fn($c, $name) => array_merge([$name], array_values($c)))
        );

        $total = collect($totalCounts)->sum(fn($c) => array_sum($c));
        $this->line("Total records to delete: {$total}");

        if ($total === 0) {
            $this->info('Nothing to reset.');
            return 0;
        }

        if (!$this->option('force') && !$this->confirm("This will permanently delete {$total} records across " . count($tenants) . " tenants. Continue?")) {
            $this->info('Cancelled.');
            return 0;
        }

        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);
            $this->line("Processing tenant: {$tenant->name}");

            $this->deleteRecords($since, $this->option('preserve-users'));

            tenancy()->end();
            $this->info("Tenant '{$tenant->name}' reset complete.");
        }

        $this->info('Demo data reset completed successfully.');
        return 0;
    }

    private function countRecords($since, ?bool $preserveUsers): array
    {
        $userQuery = User::where('created_at', '>=', $since);
        if ($preserveUsers) {
            $userQuery = User::where('created_at', '>=', $since)
                ->whereDoesntHave('roles', fn($q) => $q->whereIn('name', ['admin', 'instructor']));
        }

        return [
            'users' => (clone $userQuery)->count(),
            'enrollments' => Enrollment::where('created_at', '>=', $since)->count(),
            'courses' => Course::where('created_at', '>=', $since)->count() + Course::onlyTrashed()->where('deleted_at', '>=', $since)->count(),
            'sections' => Section::where('created_at', '>=', $since)->count() + Section::onlyTrashed()->where('deleted_at', '>=', $since)->count(),
            'lessons' => Lesson::where('created_at', '>=', $since)->count() + Lesson::onlyTrashed()->where('deleted_at', '>=', $since)->count(),
            'quizzes' => Quiz::where('created_at', '>=', $since)->count(),
            'attempts' => QuizAttempt::where('created_at', '>=', $since)->count(),
            'assignments' => Assignment::where('created_at', '>=', $since)->count(),
            'submissions' => AssignmentSubmission::where('created_at', '>=', $since)->count(),
            'progress' => LessonProgress::where('created_at', '>=', $since)->count(),
            'points' => PointsTransaction::where('created_at', '>=', $since)->count(),
            'messages' => ContactMessage::where('created_at', '>=', $since)->count(),
            'notifications' => DB::table('notifications')->where('created_at', '>=', $since)->count(),
        ];
    }

    private function deleteRecords($since, ?bool $preserveUsers): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Restore soft-deleted records first (they were "changed" — deleted today)
        Course::onlyTrashed()->where('deleted_at', '>=', $since)->each(fn($m) => $m->restore());
        Section::onlyTrashed()->where('deleted_at', '>=', $since)->each(fn($m) => $m->restore());
        Lesson::onlyTrashed()->where('deleted_at', '>=', $since)->each(fn($m) => $m->restore());

        // Delete in dependency order (children first)
        DB::table('notifications')->where('created_at', '>=', $since)->delete();
        QuizOption::where('created_at', '>=', $since)->delete();
        QuizAttempt::where('created_at', '>=', $since)->delete();
        AssignmentGrade::where('created_at', '>=', $since)->delete();
        AssignmentAttachment::where('created_at', '>=', $since)->delete();
        AssignmentSubmission::where('created_at', '>=', $since)->delete();
        LessonProgress::where('created_at', '>=', $since)->delete();
        PointsTransaction::where('created_at', '>=', $since)->delete();
        Enrollment::where('created_at', '>=', $since)->delete();
        ContactMessage::where('created_at', '>=', $since)->delete();

        QuizQuestion::where('created_at', '>=', $since)->delete();
        Lesson::where('created_at', '>=', $since)->delete();
        Assignment::where('created_at', '>=', $since)->delete();
        Quiz::where('created_at', '>=', $since)->delete();
        Section::where('created_at', '>=', $since)->delete();
        Course::where('created_at', '>=', $since)->delete();

        // Delete users last
        if ($preserveUsers) {
            User::where('created_at', '>=', $since)
                ->whereDoesntHave('roles', fn($q) => $q->whereIn('name', ['admin', 'instructor']))
                ->delete();
        } else {
            User::where('created_at', '>=', $since)->delete();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
