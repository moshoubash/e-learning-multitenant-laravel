<?php

namespace App\Notifications;

use App\Models\Tenant\Quiz;
use App\Models\Tenant\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class QuizSubmitted extends Notification
{
    use Queueable;

    public function __construct(
        public User $student,
        public Quiz $quiz,
        public int $score,
        public bool $passed,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'quiz_submitted',
            'title' => __('messages.Quiz Submitted'),
            'message' => __('messages.:student submitted :quiz with score :score%', [
                'student' => $this->student->name,
                'quiz' => $this->quiz->title,
                'score' => $this->score,
            ]),
            'action_url' => route('tenant.instructor.quizzes'),
            'actor_id' => $this->student->id,
            'actor_name' => $this->student->name,
            'actor_avatar' => $this->student->avatar,
            'object_id' => $this->quiz->id,
            'object_title' => $this->quiz->title,
        ];
    }
}
