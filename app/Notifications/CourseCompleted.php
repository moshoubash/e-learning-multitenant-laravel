<?php

namespace App\Notifications;

use App\Models\Tenant\Course;
use App\Models\Tenant\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CourseCompleted extends Notification
{
    use Queueable;

    public function __construct(
        public User $student,
        public Course $course,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'course_completed',
            'title' => __('messages.Course Completed'),
            'message' => __('messages.:student completed :course', [
                'student' => $this->student->name,
                'course' => $this->course->title,
            ]),
            'action_url' => route('tenant.instructor.courses'),
            'actor_id' => $this->student->id,
            'actor_name' => $this->student->name,
            'actor_avatar' => $this->student->avatar,
            'object_id' => $this->course->id,
            'object_title' => $this->course->title,
        ];
    }
}
