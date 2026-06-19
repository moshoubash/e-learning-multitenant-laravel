<?php

namespace App\Notifications;

use App\Models\Tenant\Quiz;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class QuizReattemptAvailable extends Notification
{
    use Queueable;

    public function __construct(
        public Quiz $quiz,
        public int $remainingAttempts,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'quiz_reattempt_available',
            'title' => __('messages.Re-attempt Available'),
            'message' => __('messages.You can retake :quiz (:remaining attempts remaining)', [
                'quiz' => $this->quiz->title,
                'remaining' => $this->remainingAttempts,
            ]),
            'action_url' => route('tenant.student.enrolled-courses'),
            'actor_id' => null,
            'actor_name' => null,
            'actor_avatar' => null,
            'object_id' => $this->quiz->id,
            'object_title' => $this->quiz->title,
        ];
    }
}
