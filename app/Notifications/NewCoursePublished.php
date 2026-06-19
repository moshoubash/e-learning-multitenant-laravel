<?php

namespace App\Notifications;

use App\Models\Tenant\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewCoursePublished extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Course $course,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'new_course_published',
            'title' => __('messages.New Course Available'),
            'message' => __('messages.:course has been published', [
                'course' => $this->course->title,
            ]),
            'action_url' => route('tenant.student.courses'),
            'actor_id' => $this->course->instructor?->id,
            'actor_name' => $this->course->instructor?->name,
            'actor_avatar' => $this->course->instructor?->avatar,
            'object_id' => $this->course->id,
            'object_title' => $this->course->title,
        ];
    }
}
