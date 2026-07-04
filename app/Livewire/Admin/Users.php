<?php

namespace App\Livewire\Admin;

use App\Imports\UsersImport;
use App\Models\Tenant\Department;
use App\Models\Tenant\User;
use App\Services\Admin\UserImportService;
use App\Services\Admin\UsersService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\TemporaryUploadedFile;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.admin')]

class Users extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'tailwind';

    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showRestoreModal = false;
    public $showImportModal = false;

    public $editingUser = null;
    public $deletingUser = null;
    public $restoringUser = null;

    // Create form fields
    public $createName = '';
    public $createEmail = '';
    public $createPassword = '';
    public $createRole = 'student';
    public $createDepartmentId = '';

    // Edit form fields
    public $editName = '';
    public $editEmail = '';
    public $editPassword = '';
    public $editRole = '';
    public $editDepartmentId = '';

    // Import
    public $importFile;
    public $importResults = [];

    public function openCreateModal()
    {
        $importService = app(UserImportService::class);
        if (! $importService->hasCapacity()) {
            Toaster::error(__('messages.Tenant has reached its maximum user limit.'));
            return;
        }
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
        $this->editDepartmentId = $this->editingUser->department_id;
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

    public function openImportModal()
    {
        $this->importFile = null;
        $this->importResults = [];
        $this->showImportModal = true;
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->showRestoreModal = false;
        $this->showImportModal = false;
        $this->resetFormFields();
    }

    public function resetCreateForm()
    {
        $this->createName = '';
        $this->createEmail = '';
        $this->createPassword = '';
        $this->createRole = 'student';
        $this->createDepartmentId = '';
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
        $this->editDepartmentId = '';
    }

    public function store()
    {
        $this->validate($this->userCreateRules());

        $importService = app(UserImportService::class);
        if (! $importService->hasCapacity()) {
            Toaster::error(__('messages.Tenant has reached its maximum user limit.'));
            return;
        }

        $this->usersService()->createUser([
            'name' => $this->createName,
            'email' => $this->createEmail,
            'password' => $this->createPassword,
            'role' => $this->createRole,
            'department_id' => $this->createDepartmentId ?: null,
        ]);

        $this->closeModal();
        Toaster::success(__('messages.User created successfully!'));
    }

    public function import()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        $path = $this->importFile->store('/');
        $extension = $this->importFile->getClientOriginalExtension();
        $localPath = tempnam(sys_get_temp_dir(), 'import_') . '.' . $extension;
        file_put_contents($localPath, Storage::disk('local')->get($path));

        $import = new UsersImport();
        Excel::import($import, $localPath);

        Storage::disk('local')->delete($path);
        unlink($localPath);

        $this->importResults = [
            'imported' => $import->imported,
            'skipped' => $import->skipped,
            'errors' => $import->errors,
        ];

        $this->importFile = null;

        if ($import->imported > 0) {
            Toaster::success(__('messages.:count users imported successfully.', ['count' => $import->imported]));
        }
        if (! empty($import->errors)) {
            Toaster::warning(__('messages.:count errors occurred during import.', ['count' => count($import->errors)]));
        }
    }

    public function downloadImportTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users-import-template.csv"',
        ];

        $columns = ['name', 'email', 'role'];
        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, ['John Doe', 'john@example.com', 'student']);
            fputcsv($file, ['Jane Smith', 'jane@example.com', 'instructor']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function update()
    {
        $this->validate($this->userUpdateRules());

        $this->usersService()->updateUser($this->editingUser, [
            'name' => $this->editName,
            'email' => $this->editEmail,
            'password' => $this->editPassword,
            'role' => $this->editRole,
            'department_id' => $this->editDepartmentId ?: null,
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
        $users->withPath('/' . trim(\Livewire\Livewire::originalPath(), '/'));

        $deletedUsers = $this->usersService()->getDeletedUsers();
        $departments = Department::orderBy('name')->get();

        $importService = app(UserImportService::class);

        return view('livewire.admin.users', [
            'users' => $users,
            'deletedUsers' => $deletedUsers,
            'departments' => $departments,
            'maxUsers' => $importService->maxUsers(),
            'currentUsers' => $importService->currentUserCount(),
            'remainingCapacity' => $importService->remainingCapacity(),
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
