<?php

namespace App\Livewire\Admin;

use App\Models\Tenant\Course;
use App\Models\Tenant\User;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Courses extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showRestoreModal = false;

    public $editingCourse = null;
    public $deletingCourse = null;
    public $restoringCourse = null;

    // Create form fields
    public $createTitle = '';
    public $createSlug = '';
    public $createDescription = '';
    public $createPrice = '';
    public $createStatus = 'draft';
    public $createInstructorId = '';

    // Edit form fields
    public $editTitle = '';
    public $editSlug = '';
    public $editDescription = '';
    public $editPrice = '';
    public $editStatus = '';
    public $editInstructorId = '';

    // Auto-generate slug from title
    public function updatedCreateTitle($value)
    {
        $this->createSlug = Str::slug($value);
    }

    public function updatedEditTitle($value)
    {
        $this->editSlug = Str::slug($value);
    }

    public function openCreateModal()
    {
        $this->resetCreateForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($id)
    {
        $this->editingCourse = Course::find($id);
        $this->editTitle = $this->editingCourse->title;
        $this->editSlug = $this->editingCourse->slug;
        $this->editDescription = $this->editingCourse->description;
        $this->editPrice = $this->editingCourse->price;
        $this->editStatus = $this->editingCourse->status;
        $this->editInstructorId = $this->editingCourse->instructor_id;
        $this->showEditModal = true;
    }

    public function openDeleteModal($id)
    {
        $this->deletingCourse = Course::find($id);
        $this->showDeleteModal = true;
    }

    public function openRestoreModal($id)
    {
        $this->restoringCourse = Course::withTrashed()->find($id);
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
        $this->createTitle = '';
        $this->createSlug = '';
        $this->createDescription = '';
        $this->createPrice = '';
        $this->createStatus = 'draft';
        $this->createInstructorId = '';
    }

    public function resetFormFields()
    {
        $this->editingCourse = null;
        $this->deletingCourse = null;
        $this->restoringCourse = null;
        $this->editTitle = '';
        $this->editSlug = '';
        $this->editDescription = '';
        $this->editPrice = '';
        $this->editStatus = '';
        $this->editInstructorId = '';
    }

    public function store()
    {
        $this->validate([
            'createTitle' => 'required|string|max:255',
            'createSlug' => 'required|string|max:255|unique:courses,slug',
            'createDescription' => 'nullable|string',
            'createPrice' => 'required|numeric|min:0',
            'createStatus' => 'required|in:draft,published,archived',
            'createInstructorId' => 'required|exists:users,id',
        ]);

        Course::create([
            'title' => $this->createTitle,
            'slug' => $this->createSlug,
            'description' => $this->createDescription,
            'price' => $this->createPrice,
            'status' => $this->createStatus,
            'instructor_id' => $this->createInstructorId,
        ]);

        $this->closeModal();
        Toaster::success('Course created successfully!');
    }

    public function update()
    {
        $this->validate([
            'editTitle' => 'required|string|max:255',
            'editSlug' => 'required|string|max:255|unique:courses,slug,' . $this->editingCourse->id,
            'editDescription' => 'nullable|string',
            'editPrice' => 'required|numeric|min:0',
            'editStatus' => 'required|in:draft,published,archived',
            'editInstructorId' => 'required|exists:users,id',
        ]);

        $this->editingCourse->title = $this->editTitle;
        $this->editingCourse->slug = $this->editSlug;
        $this->editingCourse->description = $this->editDescription;
        $this->editingCourse->price = $this->editPrice;
        $this->editingCourse->status = $this->editStatus;
        $this->editingCourse->instructor_id = $this->editInstructorId;

        $this->editingCourse->save();

        $this->closeModal();
        Toaster::success('Course updated successfully!');
    }

    public function softDelete()
    {
        if ($this->deletingCourse) {
            $this->deletingCourse->delete();
            $this->closeModal();
            Toaster::success('Course soft deleted successfully!');
        }
    }

    public function restore()
    {
        if ($this->restoringCourse) {
            $this->restoringCourse->restore();
            $this->closeModal();
            Toaster::success('Course restored successfully!');
        }
    }

    public function render()
    {
        $courses = Course::select('id', 'title', 'slug', 'price', 'status', 'instructor_id', 'created_at')
            ->with('instructor')
            ->paginate(10);

        $deletedCourses = Course::onlyTrashed()->with('instructor')->get();

        $instructors = User::role('instructor')->get();

        return view('livewire.admin.courses', [
            'courses' => $courses,
            'deletedCourses' => $deletedCourses,
            'instructors' => $instructors,
        ]);
    }
}