<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class Notifications extends Component
{
    use WithPagination;

    public $filter = 'all';

    protected $listeners = ['refreshNotifications' => '$refresh'];

    public function markAsRead($notificationId)
    {
        $user = auth()->user();
        if ($user) {
            $notification = $user->notifications()->find($notificationId);
            if ($notification && !$notification->read_at) {
                $notification->markAsRead();
            }
        }
    }

    public function markAllAsRead()
    {
        $user = auth()->user();
        if ($user) {
            $user->unreadNotifications->markAsRead();
        }
    }

    public function deleteNotification($notificationId)
    {
        $user = auth()->user();
        if ($user) {
            $user->notifications()->where('id', $notificationId)->delete();
        }
    }

    public function deleteAllRead()
    {
        $user = auth()->user();
        if ($user) {
            $user->readNotifications()->delete();
        }
    }

    public function render()
    {
        $user = auth()->user();
        $query = $user?->notifications();

        if ($this->filter === 'unread') {
            $query = $user?->unreadNotifications();
        } elseif ($this->filter === 'read') {
            $query = $user?->readNotifications();
        }

        $notifications = $query?->latest()->paginate(20) ?? collect();
        $unreadCount = $user?->unreadNotifications()->count() ?? 0;

        $layout = match (true) {
            $user?->hasRole('admin') => 'layouts.admin',
            $user?->hasRole('instructor') => 'layouts.instructor',
            default => 'layouts.student',
        };

        return view('livewire.notifications', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ])->layout($layout);
    }
}
