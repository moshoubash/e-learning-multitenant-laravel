<?php

namespace App\Livewire\Shared;

use Livewire\Component;

class NotificationBell extends Component
{
    public $unreadCount = 0;
    public $recentNotifications = [];
    public $showDropdown = false;

    protected $listeners = ['refreshNotifications' => 'loadNotifications'];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $user = auth()->user();
        if (!$user) {
            $this->unreadCount = 0;
            $this->recentNotifications = [];
            return;
        }

        $this->unreadCount = $user->unreadNotifications()->count();
        $this->recentNotifications = $user->notifications()
            ->latest()
            ->take(5)
            ->get()
            ->toArray();
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
        if ($this->showDropdown) {
            $this->loadNotifications();
        }
    }

    public function markAsRead($notificationId)
    {
        $user = auth()->user();
        if ($user) {
            $notification = $user->notifications()->find($notificationId);
            if ($notification && !$notification->read_at) {
                $notification->markAsRead();
            }
        }
        $this->loadNotifications();
    }

    public function markAllAsRead()
    {
        $user = auth()->user();
        if ($user) {
            $user->unreadNotifications->markAsRead();
        }
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.shared.notification-bell');
    }
}
