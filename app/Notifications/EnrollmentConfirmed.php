<?php

namespace App\Notifications;

use App\Models\Tenant\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EnrollmentConfirmed extends Notification
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
            'type' => 'enrollment_confirmed',
            'title' => __('messages.Enrollment Confirmed'),
            'message' => __('messages.You have successfully enrolled in :course', [
                'course' => $this->course->title,
            ]),
            'action_url' => route('tenant.student.course', $this->course->slug),
            'actor_id' => null,
            'actor_name' => null,
            'actor_avatar' => null,
            'object_id' => $this->course->id,
            'object_title' => $this->course->title,
        ];
    }
}
