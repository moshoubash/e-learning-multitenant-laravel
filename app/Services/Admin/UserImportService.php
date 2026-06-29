<?php

namespace App\Services\Admin;

use App\Models\Tenant\User;

class UserImportService
{
    public function maxUsers(): int
    {
        return tenant('max_users') ?? 3;
    }

    public function currentUserCount(): int
    {
        return User::count();
    }

    public function hasCapacity(int $additional = 1): bool
    {
        return ($this->currentUserCount() + $additional) <= $this->maxUsers();
    }

    public function remainingCapacity(): int
    {
        return max(0, $this->maxUsers() - $this->currentUserCount());
    }
}
