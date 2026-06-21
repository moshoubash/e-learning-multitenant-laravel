<?php

namespace App\Notifications;

use App\Models\Tenant\Quiz;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class QuizResult extends Notification
{
    use Queueable;

    public function __construct(
        public Quiz $quiz,
        public int $score,
        public bool $passed,
        public int $attemptNumber,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'quiz_result',
            'title' => $this->passed ? __('messages.Quiz Passed') : __('messages.Quiz Failed'),
            'message' => $this->passed
                ? __('messages.You passed :quiz with :score% on attempt :attempt', [
                    'quiz' => $this->quiz->title,
                    'score' => $this->score,
                    'attempt' => $this->attemptNumber,
                ])
                : __('messages.You scored :score% on :quiz (attempt :attempt)', [
                    'score' => $this->score,
                    'quiz' => $this->quiz->title,
                    'attempt' => $this->attemptNumber,
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
