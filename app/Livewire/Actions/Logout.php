<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke(): void
    {
        $guard = tenant() ? 'tenant' : 'web';
        $userId = auth($guard)->id();

        Auth::guard($guard)->logout();

        \App\Support\SecurityEvent::log(\App\Support\SecurityEvent::LOGOUT, [
            'guard' => $guard,
            'user_id' => $userId,
        ]);

        Session::invalidate();
        Session::regenerateToken();
    }
}
