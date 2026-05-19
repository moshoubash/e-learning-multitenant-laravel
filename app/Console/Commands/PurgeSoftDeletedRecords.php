<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Models\Tenant\Course;
use App\Models\Tenant\Lesson;
use App\Models\Tenant\Section;
use App\Models\Tenant\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PurgeSoftDeletedRecords extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:purge-soft-deleted
                            {--days=30 : Number of days after which soft-deleted records should be permanently deleted}
                            {--dry-run : Show what would be deleted without actually deleting}
                            {--tenant= : Specific tenant ID to purge (optional, defaults to all tenants)}';

    /**
     * The console command description.
     */
    protected $description = 'Permanently delete soft-deleted courses, users, sections, and lessons older than specified days';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');
        $dryRun = $this->option('dry-run');
        $tenantId = $this->option('tenant');

        $this->info("Purging soft-deleted records older than {$days} days...");

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No records will be deleted');
        }

        // Get tenants to process
        $tenants = $tenantId
            ? Tenant::where('id', $tenantId)->get()
            : Tenant::all();

        if ($tenants->isEmpty()) {
            $this->warn('No tenants found to process.');
            return self::SUCCESS;
        }

        $this->info("Found {$tenants->count()} tenant(s) to process.");

        $totalDeleted = 0;

        foreach ($tenants as $tenant) {
            $this->newLine();
            $this->info("Processing tenant: {$tenant->name} (ID: {$tenant->id})");

            // Initialize tenant context using tenancy() helper
            tenancy()->initialize($tenant);
            $totalDeleted += $this->purgeTenantRecords($days, $dryRun);
            tenancy()->end();
        }

        $this->newLine();

        if ($dryRun) {
            $this->info("DRY RUN COMPLETE: Would have permanently deleted {$totalDeleted} records.");
            Log::info("PurgeSoftDeleted: Dry run would delete {$totalDeleted} records older than {$days} days");
        } else {
            $this->info("Successfully permanently deleted {$totalDeleted} records across all tenants.");
            Log::info("PurgeSoftDeleted: Permanently deleted {$totalDeleted} records older than {$days} days");
        }

        return self::SUCCESS;
    }

    /**
     * Purge soft-deleted records within a tenant context.
     */
    protected function purgeTenantRecords(int $days, bool $dryRun): int
    {
        $cutoffDate = now()->subDays($days);
        $totalDeleted = 0;

        // Purge Courses
        $coursesDeleted = $this->purgeModel(Course::class, 'courses', $cutoffDate, $dryRun);
        $totalDeleted += $coursesDeleted;

        // Purge Users
        $usersDeleted = $this->purgeModel(User::class, 'users', $cutoffDate, $dryRun);
        $totalDeleted += $usersDeleted;

        // Purge Sections
        $sectionsDeleted = $this->purgeModel(Section::class, 'sections', $cutoffDate, $dryRun);
        $totalDeleted += $sectionsDeleted;

        // Purge Lessons
        $lessonsDeleted = $this->purgeModel(Lesson::class, 'lessons', $cutoffDate, $dryRun);
        $totalDeleted += $lessonsDeleted;

        return $totalDeleted;
    }

    /**
     * Purge soft-deleted records for a given model.
     */
    protected function purgeModel(string $modelClass, string $modelName, \DateTime $cutoffDate, bool $dryRun): int
    {
        try {
            $query = $modelClass::onlyTrashed()
                ->where('deleted_at', '<', $cutoffDate);

            $count = $query->count();

            if ($count > 0) {
                if ($dryRun) {
                    $this->line("  [DRY RUN] Would delete {$count} soft-deleted {$modelName}");
                } else {
                    $deleted = $query->forceDelete();
                    $this->line("  <fg=green;options=bold>✓</> Permanently deleted {$deleted} soft-deleted {$modelName}");
                }
            } else {
                $this->line("  No soft-deleted {$modelName} to purge");
            }

            return $count;
        } catch (\Exception $e) {
            $this->warn("  Could not process {$modelName}: {$e->getMessage()}");
            return 0;
        }
    }
}
