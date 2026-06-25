<?php

namespace App\Console\Commands;

use App\Models\Tenant\Course;
use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Models\Tenant\Lesson;
use App\Models\Tenant\Section;
use App\Models\Tenant\User;

class DeleteSoftData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-soft-data{--force : Force deletion without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isForce = $this->option('force');

        $days = 30;

        if (!is_numeric($days) || $days < 0) {
            $this->error('Invalid days parameter. Please provide a non-negative number.');
            return 1;
        }

        $cutoffDate = now()->subDays($days);

        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);

            $this->info('Processing tenant: ' . $tenant->name);

            if(!$isForce == 1){
                $courses = Course::onlyTrashed()->where('deleted_at', '<=', $cutoffDate)->get();
                $users = User::onlyTrashed()->where('deleted_at', '<=', $cutoffDate)->get();
                $sections = Section::onlyTrashed()->where('deleted_at', '<=', $cutoffDate)->get();
                $lessons = Lesson::onlyTrashed()->where('deleted_at', '<=', $cutoffDate)->get();
            } else {
                $courses = Course::onlyTrashed()->get();
                $users = User::onlyTrashed()->get();
                $sections = Section::onlyTrashed()->get();
                $lessons = Lesson::onlyTrashed()->get();
            }
            
            $courses->each->forceDelete();
            $users->each->forceDelete();
            $sections->each->forceDelete();
            $lessons->each->forceDelete();

            tenancy()->end();

            $this->info('Tenant: ' . $tenant->name . ' processed successfully.');
            $this->info('----------------------------------------------------');
        }

        $this->info('All soft data deleted successfully.');

        return 0;
    }
}
