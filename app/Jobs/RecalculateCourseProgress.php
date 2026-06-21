<?php

namespace App\Jobs;

use App\Models\Tenant\Enrollment;
use App\Models\Tenant\Lesson;
use App\Models\Tenant\LessonProgress;
use App\Models\Tenant\User;
use App\Notifications\CourseCompleted;
use App\Notifications\CourseCompletedStudent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecalculateCourseProgress implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [10, 30, 90];

    public function __construct(
        public int $courseId,
        public int $userId,
    ) {
    }

    public function handle(): int
    {
        $totalLessons = Lesson::whereHas('section', function ($query) {
            $query->where('course_id', $this->courseId);
        })->count();

        if ($totalLessons === 0) {
            return 0;
        }

        $completedLessons = LessonProgress::where('user_id', $this->userId)
            ->whereHas('lesson.section', function ($query) {
                $query->where('course_id', $this->courseId);
            })
            ->where('is_completed', true)
            ->count();

        $progressPercent = (int) round(($completedLessons / $totalLessons) * 100);

        Enrollment::where('course_id', $this->courseId)
            ->where('user_id', $this->userId)
            ->update([
                'progress_percent' => $progressPercent,
                'completed_at' => $progressPercent === 100 ? now() : null,
                'status' => $progressPercent === 100
                    ? Enrollment::STATUS_COMPLETED
                    : Enrollment::STATUS_ACTIVE,
            ]);

        if ($progressPercent === 100) {
            $course = \App\Models\Tenant\Course::with('instructor')->find($this->courseId);
            $student = User::find($this->userId);
            if ($course && $student) {
                if ($course->instructor) {
                    $course->instructor->notify(new CourseCompleted($student, $course));
                }
                $student->notify(new CourseCompletedStudent($course));
            }
        }

        return $progressPercent;
    }
}
