<?php

namespace App\Services\Admin;

use App\Models\Tenant\User;
use Illuminate\Support\Facades\Hash;

class UsersService
{
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function findWithTrashed(int $id): ?User
    {
        return User::withTrashed()->find($id);
    }

    public function getPaginatedUsers(int $perPage = 10)
    {
        return User::select('id', 'name', 'email', 'created_at')->with('roles')->paginate($perPage);
    }

    public function getDeletedUsers()
    {
        return User::onlyTrashed()->get();
    }

    public function createUser(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (! empty($data['role'])) {
            $user->assignRole($data['role']);
        }

        return $user;
    }

    public function updateUser(User $user, array $data): User
    {
        $user->name = $data['name'];
        $user->email = $data['email'];

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        if (! empty($data['role'])) {
            $user->syncRoles([$data['role']]);
        }

        return $user;
    }

    public function softDeleteUser(User $user): void
    {
        $user->delete();
    }

    public function restoreUser(User $user): void
    {
        $user->restore();
    }
}
