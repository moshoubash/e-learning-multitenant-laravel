<?php

namespace App\Livewire;

use App\Models\Tenant\User;
use App\Notifications\AdminBroadcast;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Notifications extends Component
{
    use WithPagination;

    public $filter = 'all';

    public $showSendModal = false;
    public $sendTitle = '';
    public $sendMessage = '';
    public $sendRecipientType = 'all_students';
    public $sendSpecificUsers = [];
    public $userSearch = '';

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

    public function openSendModal()
    {
        $this->resetSendForm();
        $this->showSendModal = true;
    }

    public function closeSendModal()
    {
        $this->showSendModal = false;
        $this->resetSendForm();
    }

    public function resetSendForm()
    {
        $this->sendTitle = '';
        $this->sendMessage = '';
        $this->sendRecipientType = 'all_students';
        $this->sendSpecificUsers = [];
        $this->userSearch = '';
    }

    public function send()
    {
        $this->validate([
            'sendTitle' => 'required|string|max:255',
            'sendMessage' => 'required|string|max:5000',
            'sendRecipientType' => 'required|in:all_users,all_instructors,all_students,specific',
            'sendSpecificUsers' => 'required_if:sendRecipientType,specific|array',
            'sendSpecificUsers.*' => 'exists:users,id',
        ]);

        $adminName = auth()->user()->name;

        $query = match ($this->sendRecipientType) {
            'all_users' => User::query(),
            'all_instructors' => User::role('instructor'),
            'all_students' => User::role('student'),
            'specific' => User::whereIn('id', $this->sendSpecificUsers),
            default => User::query(),
        };

        $notification = new AdminBroadcast(
            title: $this->sendTitle,
            message: $this->sendMessage,
            actionUrl: route('tenant.notifications'),
        );

        $count = 0;
        $query->chunk(100, function ($users) use ($notification, &$count) {
            foreach ($users as $user) {
                $user->notify($notification);
                $count++;
            }
        });

        $this->closeSendModal();
        Toaster::success(__('messages.Notification sent to :count users.', ['count' => $count]));
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

        $notifications = $query?->latest()->paginate(10) ?? collect();
        if ($notifications instanceof \Illuminate\Contracts\Pagination\Paginator) {
            $notifications->withPath('/' . trim(\Livewire\Livewire::originalPath(), '/'));
        }
        $unreadCount = $user?->unreadNotifications()->count() ?? 0;

        $layout = match (true) {
            $user?->hasRole('admin') => 'layouts.admin',
            $user?->hasRole('instructor') => 'layouts.instructor',
            default => 'layouts.student',
        };

        $users = [];
        if ($this->showSendModal) {
            $usersQuery = User::query();
            if ($this->userSearch) {
                $usersQuery->where(function ($q) {
                    $q->where('name', 'like', "%{$this->userSearch}%")
                      ->orWhere('email', 'like', "%{$this->userSearch}%");
                });
            }
            $users = $usersQuery->orderBy('name')->get(['id', 'name', 'email']);
        }

        return view('livewire.notifications', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'users' => $users,
        ])->layout($layout);
    }
}
