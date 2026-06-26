<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

#[Layout('layouts.admin')]
class RolesAndPermissions extends Component
{
    use WithPagination;

    private const GUARD = 'tenant';

    #[Url(as: 'tab')]
    public string $activeTab = 'roles';

    // Role CRUD
    public $showRoleCreateModal = false;
    public $showRoleEditModal = false;
    public $showRoleDeleteModal = false;
    public $editingRole = null;
    public $deletingRole = null;
    public $roleName = '';
    public $roleEditName = '';

    // Permission CRUD
    public $showPermissionCreateModal = false;
    public $showPermissionEditModal = false;
    public $showPermissionDeleteModal = false;
    public $editingPermission = null;
    public $deletingPermission = null;
    public $permissionName = '';
    public $permissionEditName = '';

    // Role-Permission assignment
    public $showAssignModal = false;
    public $assigningRole = null;
    public $assignedPermissions = [];

    private const PROTECTED_ROLES = ['admin', 'instructor', 'student'];

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    private function findRole(int $id): ?Role
    {
        return Role::where('guard_name', self::GUARD)->find($id);
    }

    private function findPermission(int $id): ?Permission
    {
        return Permission::where('guard_name', self::GUARD)->find($id);
    }

    //  Role CRUD

    public function openRoleCreateModal(): void
    {
        $this->roleName = '';
        $this->showRoleCreateModal = true;
    }

    public function openRoleEditModal(int $id): void
    {
        $this->editingRole = $this->findRole($id);
        if (! $this->editingRole) {
            Toaster::error(__('messages.Role not found.'));
            return;
        }
        $this->roleEditName = $this->editingRole->name;
        $this->showRoleEditModal = true;
    }

    public function openRoleDeleteModal(int $id): void
    {
        $this->deletingRole = $this->findRole($id);
        $this->showRoleDeleteModal = true;
    }

    public function closeModals(): void
    {
        $this->showRoleCreateModal = false;
        $this->showRoleEditModal = false;
        $this->showRoleDeleteModal = false;
        $this->showPermissionCreateModal = false;
        $this->showPermissionEditModal = false;
        $this->showPermissionDeleteModal = false;
        $this->showAssignModal = false;
        $this->editingRole = null;
        $this->deletingRole = null;
        $this->editingPermission = null;
        $this->deletingPermission = null;
        $this->assigningRole = null;
        $this->assignedPermissions = [];
    }

    public function storeRole(): void
    {
        $this->validate([
            'roleName' => 'required|string|max:255|unique:roles,name',
        ]);

        Role::create(['name' => $this->roleName, 'guard_name' => self::GUARD]);

        $this->closeModals();
        Toaster::success(__('messages.Role created successfully!'));
    }

    public function updateRole(): void
    {
        $this->validate([
            'roleEditName' => 'required|string|max:255|unique:roles,name,' . $this->editingRole->id,
        ]);

        if ($this->editingRole) {
            $this->editingRole->update(['name' => $this->roleEditName]);
        }

        $this->closeModals();
        Toaster::success(__('messages.Role updated successfully!'));
    }

    public function deleteRole(): void
    {
        if ($this->deletingRole && ! in_array($this->deletingRole->name, ['admin', 'instructor', 'student'])) {
            $this->deletingRole->delete();
            $this->closeModals();
            Toaster::success(__('messages.Role deleted successfully!'));
        }
    }

    //  Permission CRUD

    public function openPermissionCreateModal(): void
    {
        $this->permissionName = '';
        $this->showPermissionCreateModal = true;
    }

    public function openPermissionEditModal(int $id): void
    {
        $this->editingPermission = $this->findPermission($id);
        if (! $this->editingPermission) {
            Toaster::error(__('messages.Permission not found.'));
            return;
        }
        $this->permissionEditName = $this->editingPermission->name;
        $this->showPermissionEditModal = true;
    }

    public function openPermissionDeleteModal(int $id): void
    {
        $this->deletingPermission = $this->findPermission($id);
        $this->showPermissionDeleteModal = true;
    }

    public function storePermission(): void
    {
        $this->validate([
            'permissionName' => 'required|string|max:255|unique:permissions,name',
        ]);

        Permission::create(['name' => $this->permissionName, 'guard_name' => self::GUARD]);

        $this->closeModals();
        Toaster::success(__('messages.Permission created successfully!'));
    }

    public function updatePermission(): void
    {
        $this->validate([
            'permissionEditName' => 'required|string|max:255|unique:permissions,name,' . $this->editingPermission->id,
        ]);

        if ($this->editingPermission) {
            $this->editingPermission->update(['name' => $this->permissionEditName]);
        }

        $this->closeModals();
        Toaster::success(__('messages.Permission updated successfully!'));
    }

    public function deletePermission(): void
    {
        if ($this->deletingPermission) {
            $this->deletingPermission->delete();
            $this->closeModals();
            Toaster::success(__('messages.Permission deleted successfully!'));
        }
    }

    //  Role-Permission Assignment

    public function openAssignModal(int $roleId): void
    {
        $this->assigningRole = $this->findRole($roleId);
        if (! $this->assigningRole) {
            Toaster::error(__('messages.Role not found.'));
            return;
        }
        $this->assignedPermissions = $this->assigningRole->permissions->pluck('id')->map(fn($id) => (string) $id)->toArray();
        $this->showAssignModal = true;
    }

    public function savePermissions(): void
    {
        if (! $this->assigningRole) {
            Toaster::error(__('messages.Role not found.'));
            return;
        }

        $permissionIds = array_filter($this->assignedPermissions, fn($id) => ! empty($id));
        $this->assigningRole->syncPermissions(Permission::whereIn('id', $permissionIds)->get());

        $this->closeModals();
        Toaster::success(__('messages.Permissions updated successfully!'));
    }

    //  Render

    public function render()
    {
        $path = '/' . trim(\Livewire\Livewire::originalPath(), '/');

        $roles = Role::where('guard_name', self::GUARD)->orderBy('name')->paginate(10, ['*'], 'rolesPage');
        $roles->withPath($path)->appends('tab', $this->activeTab);

        $permissions = Permission::where('guard_name', self::GUARD)->orderBy('name')->paginate(10, ['*'], 'permissionsPage');
        $permissions->withPath($path)->appends('tab', $this->activeTab);

        return view('livewire.admin.roles-and-permissions', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }
}
