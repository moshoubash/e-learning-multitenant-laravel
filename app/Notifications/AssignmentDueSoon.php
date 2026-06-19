<?php

namespace App\Notifications;

use App\Models\Tenant\Assignment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AssignmentDueSoon extends Notification
{
    use Queueable;

    public function __construct(
        public Assignment $assignment,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'assignment_due_soon',
            'title' => __('messages.Assignment Due Soon'),
            'message' => __('messages.:assignment is due :date', [
                'assignment' => $this->assignment->title,
                'date' => $this->assignment->due_date?->format('M d, Y') ?? 'N/A',
            ]),
            'action_url' => route('tenant.student.enrolled-courses'),
            'actor_id' => null,
            'actor_name' => null,
            'actor_avatar' => null,
            'object_id' => $this->assignment->id,
            'object_title' => $this->assignment->title,
        ];
    }
}
