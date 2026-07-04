<?php

namespace App\Livewire\Admin;

use App\Models\Tenant\Department;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Masmerise\Toaster\Toaster;

#[Layout('layouts.admin')]
class Departments extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showRestoreModal = false;

    public $editingDepartment = null;
    public $deletingDepartment = null;
    public $restoringDepartment = null;

    public $createName = '';
    public $createDescription = '';

    public $editName = '';
    public $editDescription = '';

    public function openCreateModal()
    {
        $this->resetCreateForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($id)
    {
        $this->editingDepartment = Department::find($id);
        if (! $this->editingDepartment) {
            Toaster::error('Department not found.');
            return;
        }
        $this->editName = $this->editingDepartment->name;
        $this->editDescription = $this->editingDepartment->description;
        $this->showEditModal = true;
    }

    public function openDeleteModal($id)
    {
        $this->deletingDepartment = Department::find($id);
        $this->showDeleteModal = true;
    }

    public function openRestoreModal($id)
    {
        $this->restoringDepartment = Department::withTrashed()->find($id);
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
        $this->createDescription = '';
    }

    public function resetFormFields()
    {
        $this->editingDepartment = null;
        $this->deletingDepartment = null;
        $this->restoringDepartment = null;
        $this->editName = '';
        $this->editDescription = '';
    }

    public function store()
    {
        $this->validate([
            'createName' => 'required|string|max:255',
            'createDescription' => 'nullable|string|max:1000',
        ]);

        $slug = Str::slug($this->createName);
        $originalSlug = $slug;
        $count = 1;
        while (Department::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        Department::create([
            'name' => $this->createName,
            'slug' => $slug,
            'description' => $this->createDescription,
        ]);

        $this->closeModal();
        Toaster::success('messages.Department created successfully!');
    }

    public function update()
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editDescription' => 'nullable|string|max:1000',
        ]);

        if (! $this->editingDepartment) {
            Toaster::error('Department not found.');
            return;
        }

        $slug = Str::slug($this->editName);
        $originalSlug = $slug;
        $count = 1;
        while (Department::where('slug', $slug)->where('id', '!=', $this->editingDepartment->id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $this->editingDepartment->update([
            'name' => $this->editName,
            'slug' => $slug,
            'description' => $this->editDescription,
        ]);

        $this->closeModal();
        Toaster::success('messages.Department updated successfully!');
    }

    public function softDelete()
    {
        if ($this->deletingDepartment) {
            $this->deletingDepartment->delete();
            $this->closeModal();
            Toaster::success('messages.Department deleted successfully!');
        }
    }

    public function restore()
    {
        if ($this->restoringDepartment) {
            $this->restoringDepartment->restore();
            $this->closeModal();
            Toaster::success('messages.Department restored successfully!');
        }
    }

    public function render()
    {
        $departments = Department::withCount('users', 'courses')
            ->orderBy('name')
            ->paginate(10);
        $departments->withPath('/' . trim(\Livewire\Livewire::originalPath(), '/'));

        $deletedDepartments = Department::onlyTrashed()->get();

        return view('livewire.admin.departments', [
            'departments' => $departments,
            'deletedDepartments' => $deletedDepartments,
        ]);
    }
}
