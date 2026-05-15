<?php

namespace App\Livewire\Admin;

use App\Models\Tenant\User;
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
        $this->editingUser = User::find($id);
        $this->editName = $this->editingUser->name;
        $this->editEmail = $this->editingUser->email;
        $this->editRole = $this->editingUser->getRoleNames()->first() ?? 'student';
        $this->editPassword = '';
        $this->showEditModal = true;
    }

    public function openDeleteModal($id)
    {
        $this->deletingUser = User::find($id);
        $this->showDeleteModal = true;
    }

    public function openRestoreModal($id)
    {
        $this->restoringUser = User::withTrashed()->find($id);
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
        $this->validate([
            'createName' => 'required|string|max:255',
            'createEmail' => 'required|email|unique:users,email',
            'createPassword' => 'required|min:8',
            'createRole' => 'required|in:admin,instructor,student',
        ]);

        $user = User::create([
            'name' => $this->createName,
            'email' => $this->createEmail,
            'password' => Hash::make($this->createPassword),
        ]);

        $user->assignRole($this->createRole);

        $this->closeModal();
        Toaster::success('User created successfully!');
    }

    public function update()
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editEmail' => 'required|email|unique:users,email,' . $this->editingUser->id,
            'editPassword' => 'nullable|min:8',
            'editRole' => 'required|in:admin,instructor,student',
        ]);

        $this->editingUser->name = $this->editName;
        $this->editingUser->email = $this->editEmail;

        if ($this->editPassword) {
            $this->editingUser->password = Hash::make($this->editPassword);
        }

        $this->editingUser->save();
        $this->editingUser->syncRoles([$this->editRole]);

        $this->closeModal();
        Toaster::success('User updated successfully!');
    }

    public function softDelete()
    {
        if ($this->deletingUser) {
            $this->deletingUser->delete();
            $this->closeModal();
            Toaster::success('User soft deleted successfully!');
        }
    }

    public function restore()
    {
        if ($this->restoringUser) {
            $this->restoringUser->restore();
            $this->closeModal();
            Toaster::success('User restored successfully!');
        }
    }

    public function render()
    {
        $users = User::select('id', 'name', 'email', 'created_at')
            ->with('roles')
            ->paginate(10);

        $deletedUsers = User::onlyTrashed()->get();

        return view('livewire.admin.users', [
            'users' => $users,
            'deletedUsers' => $deletedUsers,
        ]);
    }
}
