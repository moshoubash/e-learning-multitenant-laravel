<?php

namespace App\Livewire\Admin;

use App\Models\Tenant\Course;
use App\Models\Tenant\User;
use App\Services\Admin\CoursesService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.admin')]

class Courses extends Component
{
    use WithPagination, WithFileUploads;

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
    public $createThumbnail = null;

    // Edit form fields
    public $editTitle = '';
    public $editDescription = '';
    public $editPrice = 0;
    public $editStatus = '';
    public $editInstructorId = '';
    public $editThumbnail = null;

    public $showRestoreModal = false;
    public $restoringCourse = null;

    public function openRestoreModal($id)
    {
        $this->restoringCourse = $this->courseService()->findWithTrashed($id);
        $this->showRestoreModal = true;
    }

    public function openCreateModal()
    {
        $this->resetCreateForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($id)
    {
        $this->editingCourse = $this->courseService()->findById($id);
        if (! $this->editingCourse) {
            Toaster::error('Course not found.');
            return;
        }
        $this->editTitle = $this->editingCourse->title;
        $this->editDescription = $this->editingCourse->description;
        $this->editPrice = $this->editingCourse->price;
        $this->editStatus = $this->editingCourse->status;
        $this->editInstructorId = $this->editingCourse->instructor_id;
        $this->showEditModal = true;
    }

    public function openDeleteModal($id)
    {
        $this->deletingCourse = $this->courseService()->findById($id);
        $this->showDeleteModal = true;
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->showRestoreModal = false;
        $this->resetFormFields();
    }

    public function restore()
    {
        if ($this->restoringCourse) {
            $this->courseService()->restoreCourse($this->restoringCourse);
            $this->closeModal();
            Toaster::success('messages.Course restored successfully!');
        }
    }

    public function resetCreateForm()
    {
        $this->createTitle = '';
        $this->createDescription = '';
        $this->createPrice = 0;
        $this->createStatus = 'draft';
        $this->createInstructorId = '';
        $this->createThumbnail = null;
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
        $this->editThumbnail = null;
    }

    public function store()
    {
        $this->validate($this->courseCreateRules());

        $data = [
            'instructor_id' => $this->createInstructorId,
            'title' => $this->createTitle,
            'description' => $this->createDescription,
            'price' => $this->createPrice,
            'status' => $this->createStatus,
        ];

        if ($this->createThumbnail) {
            $data['thumbnail'] = 'https://d1w6oovjx4x1vx.cloudfront.net/' . $this->createThumbnail->store('thumbnails', 's3');
        }

        $this->courseService()->createCourse($data);

        $this->closeModal();
        Toaster::success('messages.Course created successfully!');
    }

    public function update()
    {
        $this->validate($this->courseUpdateRules());

        if (! $this->editingCourse) {
            Toaster::error('Course not found.');
            return;
        }

        $data = [
            'instructor_id' => $this->editInstructorId,
            'title' => $this->editTitle,
            'description' => $this->editDescription,
            'price' => $this->editPrice,
            'status' => $this->editStatus,
        ];

        if ($this->editThumbnail) {
            if ($this->editingCourse->thumbnail) {
                Storage::disk('s3')->delete($this->editingCourse->thumbnail);
            }
            $data['thumbnail'] = 'https://d1w6oovjx4x1vx.cloudfront.net/' . $this->editThumbnail->store('thumbnails', 's3');
        }

        $this->courseService()->updateCourse($this->editingCourse, $data);

        $this->closeModal();
        Toaster::success('messages.Course updated successfully!');
    }

    public function softDelete()
    {
        if ($this->deletingCourse) {
            $this->courseService()->softDeleteCourse($this->deletingCourse);
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
        $courses = $this->courseService()->getPaginatedCourses(10);
        $courses->withPath('/' . trim(\Livewire\Livewire::originalPath(), '/'));

        $instructors = $this->getInstructors();

        return view('livewire.admin.courses', [
            'courses' => $courses,
            'instructors' => $instructors,
            'deletedCourses' => $this->courseService()->getDeletedCourses(),
        ]);
    }

    protected function courseCreateRules(): array
    {
        return [
            'createTitle' => 'required|string|max:255',
            'createDescription' => 'nullable|string',
            'createPrice' => 'required|numeric|min:0',
            'createStatus' => 'required|in:draft,published,archived',
            'createInstructorId' => 'required|exists:users,id',
            'createThumbnail' => 'nullable|image|mimes:jpg,png,webp|max:2048',
        ];
    }

    protected function courseUpdateRules(): array
    {
        return [
            'editTitle' => 'required|string|max:255',
            'editDescription' => 'nullable|string',
            'editPrice' => 'required|numeric|min:0',
            'editStatus' => 'required|in:draft,published,archived',
            'editInstructorId' => 'required|exists:users,id',
            'editThumbnail' => 'nullable|image|mimes:jpg,png,webp|max:2048',
        ];
    }

    protected function courseService(): CoursesService
    {
        return new CoursesService();
    }
}
