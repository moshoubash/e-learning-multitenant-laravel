<?php

namespace App\Livewire\Admin;

use App\Models\Tenant\User;
use App\Services\Admin\UsersService;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Hash;

class Users extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showRestoreModal = false;

    public $editingUser = null;
    public $deletingUser = null;
    public $restoringUser = null;

    // Create form fields
    public $createName = '';
    public $createEmail = '';
    public $createPassword = '';
    public $createRole = 'student';

    // Edit form fields
    public $editName = '';
    public $editEmail = '';
    public $editPassword = '';
    public $editRole = '';

    public function openCreateModal()
    {
        $this->resetCreateForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($id)
    {
        $this->editingUser = $this->usersService()->findById($id);
        if (! $this->editingUser) {
            Toaster::error('User not found.');
            return;
        }
        $this->editName = $this->editingUser->name;
        $this->editEmail = $this->editingUser->email;
        $this->editRole = $this->editingUser->getRoleNames()->first() ?? 'student';
        $this->editPassword = '';
        $this->showEditModal = true;
    }

    public function openDeleteModal($id)
    {
        $this->deletingUser = $this->usersService()->findById($id);
        $this->showDeleteModal = true;
    }

    public function openRestoreModal($id)
    {
        $this->restoringUser = $this->usersService()->findWithTrashed($id);
        $this->showRestoreModal = true;
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->showRestoreModal = false;
        $this->resetFormFields();
    }

    public function resetCreateForm()
    {
        $this->createName = '';
        $this->createEmail = '';
        $this->createPassword = '';
        $this->createRole = 'student';
    }

    public function resetFormFields()
    {
        $this->editingUser = null;
        $this->deletingUser = null;
        $this->restoringUser = null;
        $this->editName = '';
        $this->editEmail = '';
        $this->editPassword = '';
        $this->editRole = '';
    }

    public function store()
    {
        $this->validate($this->userCreateRules());

        $this->usersService()->createUser([
            'name' => $this->createName,
            'email' => $this->createEmail,
            'password' => $this->createPassword,
            'role' => $this->createRole,
        ]);

        $this->closeModal();
        Toaster::success('messages.User created successfully!');
    }

    public function update()
    {
        $this->validate($this->userUpdateRules());

        $this->usersService()->updateUser($this->editingUser, [
            'name' => $this->editName,
            'email' => $this->editEmail,
            'password' => $this->editPassword,
            'role' => $this->editRole,
        ]);

        $this->closeModal();
        Toaster::success('messages.User updated successfully!');
    }

    public function softDelete()
    {
        if ($this->deletingUser) {
            $this->usersService()->softDeleteUser($this->deletingUser);
            $this->closeModal();
            Toaster::success('messages.User soft deleted successfully!');
        }
    }

    public function restore()
    {
        if ($this->restoringUser) {
            $this->usersService()->restoreUser($this->restoringUser);
            $this->closeModal();
            Toaster::success('messages.User restored successfully!');
        }
    }

    public function render()
    {
        $users = $this->usersService()->getPaginatedUsers(10);

        $deletedUsers = $this->usersService()->getDeletedUsers();

        return view('livewire.admin.users', [
            'users' => $users,
            'deletedUsers' => $deletedUsers,
        ]);
    }

    protected function userCreateRules(): array
    {
        return [
            'createName' => 'required|string|max:255',
            'createEmail' => 'required|email|unique:users,email',
            'createPassword' => 'required|min:8',
            'createRole' => 'required|in:admin,instructor,student',
        ];
    }

    protected function userUpdateRules(): array
    {
        return [
            'editName' => 'required|string|max:255',
            'editEmail' => 'required|email|unique:users,email,' . optional($this->editingUser)->id,
            'editPassword' => 'nullable|min:8',
            'editRole' => 'required|in:admin,instructor,student',
        ];
    }

    protected function usersService(): UsersService
    {
        return new UsersService();
    }
}
