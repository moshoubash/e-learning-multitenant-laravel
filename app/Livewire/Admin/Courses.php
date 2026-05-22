<?php

namespace App\Livewire\Admin;

use App\Models\Tenant\Course;
use App\Models\Tenant\User;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Str;

class Courses extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    public $editingCourse = null;
    public $deletingCourse = null;

    // Create form fields
    public $createTitle = '';
    public $createDescription = '';
    public $createPrice = 0;
    public $createStatus = 'draft';
    public $createInstructorId = '';

    // Edit form fields
    public $editTitle = '';
    public $editDescription = '';
    public $editPrice = 0;
    public $editStatus = '';
    public $editInstructorId = '';

    public function openCreateModal()
    {
        $this->resetCreateForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($id)
    {
        $this->editingCourse = Course::find($id);
        $this->editTitle = $this->editingCourse->title;
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

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->resetFormFields();
    }

    public function resetCreateForm()
    {
        $this->createTitle = '';
        $this->createDescription = '';
        $this->createPrice = 0;
        $this->createStatus = 'draft';
        $this->createInstructorId = '';
    }

    public function resetFormFields()
    {
        $this->editingCourse = null;
        $this->deletingCourse = null;
        $this->editTitle = '';
        $this->editDescription = '';
        $this->editPrice = 0;
        $this->editStatus = '';
        $this->editInstructorId = '';
    }

    public function store()
    {
        $this->validate([
            'createTitle' => 'required|string|max:255',
            'createDescription' => 'nullable|string',
            'createPrice' => 'required|numeric|min:0',
            'createStatus' => 'required|in:draft,published,archived',
            'createInstructorId' => 'required|exists:users,id',
        ]);

        $slug = Str::slug($this->createTitle);

        // Ensure unique slug
        $originalSlug = $slug;
        $count = 1;
        while (Course::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        Course::create([
            'instructor_id' => $this->createInstructorId,
            'title' => $this->createTitle,
            'slug' => $slug,
            'description' => $this->createDescription,
            'price' => $this->createPrice,
            'status' => $this->createStatus,
        ]);

        $this->closeModal();
        Toaster::success('messages.Course created successfully!');
    }

    public function update()
    {
        $this->validate([
            'editTitle' => 'required|string|max:255',
            'editDescription' => 'nullable|string',
            'editPrice' => 'required|numeric|min:0',
            'editStatus' => 'required|in:draft,published,archived',
            'editInstructorId' => 'required|exists:users,id',
        ]);

        $slug = Str::slug($this->editTitle);

        // Ensure unique slug (excluding current course)
        $originalSlug = $slug;
        $count = 1;
        while (Course::where('slug', $slug)->where('id', '!=', $this->editingCourse->id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $this->editingCourse->instructor_id = $this->editInstructorId;
        $this->editingCourse->title = $this->editTitle;
        $this->editingCourse->slug = $slug;
        $this->editingCourse->description = $this->editDescription;
        $this->editingCourse->price = $this->editPrice;
        $this->editingCourse->status = $this->editStatus;
        $this->editingCourse->save();

        $this->closeModal();
        Toaster::success('messages.Course updated successfully!');
    }

    public function softDelete()
    {
        if ($this->deletingCourse) {
            $this->deletingCourse->delete();
            $this->closeModal();
            Toaster::success('messages.Course deleted successfully!');
        }
    }

    public function getInstructors()
    {
        return User::role('instructor')->get();
    }

    public function render()
    {
        $courses = Course::with('instructor')
            ->paginate(10);

        $instructors = $this->getInstructors();

        return view('livewire.admin.courses', [
            'courses' => $courses,
            'instructors' => $instructors,
        ]);
    }
}
