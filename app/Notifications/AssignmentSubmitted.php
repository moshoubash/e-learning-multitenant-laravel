<?php

namespace App\Notifications;

use App\Models\Tenant\Assignment;
use App\Models\Tenant\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AssignmentSubmitted extends Notification
{
    use Queueable;

    public function __construct(
        public User $student,
        public Assignment $assignment,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'assignment_submitted',
            'title' => __('messages.Assignment Submitted'),
            'message' => __('messages.:student submitted :assignment', [
                'student' => $this->student->name,
                'assignment' => $this->assignment->title,
            ]),
            'action_url' => route('tenant.instructor.assignments'),
            'actor_id' => $this->student->id,
            'actor_name' => $this->student->name,
            'actor_avatar' => $this->student->avatar,
            'object_id' => $this->assignment->id,
            'object_title' => $this->assignment->title,
        ];
    }
}
