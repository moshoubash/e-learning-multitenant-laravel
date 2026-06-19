<?php

namespace App\Console\Commands;

use App\Models\Tenant\Assignment;
use App\Models\Tenant\Enrollment;
use App\Notifications\AssignmentDueSoon;
use Illuminate\Console\Command;

class NotifyAssignmentDueSoon extends Command
{
    protected $signature = 'notifications:assignment-due-soon';

    protected $description = 'Notify students about assignments due within 24 hours';

    public function handle(): int
    {
        $assignments = Assignment::with('section.course')
            ->where('status', 'published')
            ->whereNotNull('due_date')
            ->where('due_date', '>=', now())
            ->where('due_date', '<=', now()->addDay())
            ->get();

        $count = 0;

        foreach ($assignments as $assignment) {
            $course = $assignment->section?->course;
            if (!$course) {
                continue;
            }

            $students = Enrollment::where('course_id', $course->id)
                ->where('status', Enrollment::STATUS_ACTIVE)
                ->with('user')
                ->get();

            foreach ($students as $enrollment) {
                if ($enrollment->user) {
                    $enrollment->user->notify(new AssignmentDueSoon($assignment));
                    $count++;
                }
            }
        }

        $this->info("Sent {$count} assignment due-soon notifications.");

        return Command::SUCCESS;
    }
}
