<?php

namespace App\Notifications;

use App\Models\Tenant\Assignment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AssignmentGraded extends Notification
{
    use Queueable;

    public function __construct(
        public Assignment $assignment,
        public int $score,
        public ?string $feedback,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'assignment_graded',
            'title' => __('messages.Assignment Graded'),
            'message' => __('messages.:assignment graded with :score%', [
                'assignment' => $this->assignment->title,
                'score' => $this->score,
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
