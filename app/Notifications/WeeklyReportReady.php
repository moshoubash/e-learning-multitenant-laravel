<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class WeeklyReportReady extends Notification
{
    use Queueable;

    public function __construct(
        public string $pdfContent,
        public array $data,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('messages.Weekly Report - :app', ['app' => config('app.name')]))
            ->greeting(__('messages.Hello, :name', ['name' => $notifiable->name]))
            ->line(__('messages.Here is your weekly analytics report for :start to :end', [
                'start' => $this->data['period_start'],
                'end' => $this->data['period_end'],
            ]))
            ->line(__('messages.New Users: :count', ['count' => $this->data['new_users_week']]))
            ->line(__('messages.New Enrollments: :count', ['count' => $this->data['new_enrollments_week']]))
            ->line(__('messages.New Courses: :count', ['count' => $this->data['new_courses_week']]))
            ->line(__('messages.Quiz Attempts: :count', ['count' => $this->data['quiz_attempts_week']]))
            ->line(__('messages.Pending Submissions: :count', ['count' => $this->data['pending_submissions']]))
            ->attachData($this->pdfContent, 'weekly-report.pdf', ['mime' => 'application/pdf'])
            ->action(__('messages.View Reports'), route('tenant.admin.reports'));
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'weekly_report',
            'title' => __('messages.Weekly Report Ready'),
            'message' => __('messages.Your weekly analytics report has been generated and emailed to you.'),
            'action_url' => route('tenant.admin.reports'),
            'actor_id' => null,
            'actor_name' => null,
            'actor_avatar' => null,
            'object_id' => null,
            'object_title' => __('messages.Weekly Report'),
        ];
    }
}
