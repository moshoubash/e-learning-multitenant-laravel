<?php

namespace App\Notifications;

use App\Models\Tenant\Course;
use App\Models\Tenant\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewEnrollment extends Notification
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
            'type' => 'new_enrollment',
            'title' => __('messages.New Enrollment'),
            'message' => __('messages.:student enrolled in :course', [
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
