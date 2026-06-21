<?php

namespace App\Notifications;

use App\Models\Tenant\Assignment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AssignmentReturned extends Notification
{
    use Queueable;

    public function __construct(
        public Assignment $assignment,
        public ?string $feedback,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'assignment_returned',
            'title' => __('messages.Assignment Returned'),
            'message' => __('messages.:assignment was returned for revision', [
                'assignment' => $this->assignment->title,
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
