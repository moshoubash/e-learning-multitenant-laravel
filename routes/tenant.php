<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| These routes are loaded via bootstrap/app.php when a tenant
| is identified via the domain/subdomain.
|
*/

// Tenant Home / Dashboard

Route::get('/', function () {
    return redirect()->route('tenant.dashboard');
});

Route::middleware(['auth:tenant'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('tenant.dashboard');

    Route::get('/profile', function () {
        return view('profile');
    })->name('tenant.profile');

    Route::livewire('/admin/users', 'admin.users')->middleware('role:admin')->name('tenant.admin.users');
    Route::livewire('/admin/courses', 'admin.courses')->middleware('role:admin')->name('tenant.admin.courses');
    Route::livewire('/admin/quizzes', 'admin.quizzes')->middleware('role:admin')->name('tenant.admin.quizzes');

    Route::livewire('/instructor/courses', 'instructor.courses')->middleware('role:instructor')->name('tenant.instructor.courses');
    Route::livewire('/instructor/quizzes', 'instructor.quizzes')->middleware('role:instructor')->name('tenant.instructor.quizzes');
    Route::livewire('/instructor/assignments', 'instructor.assignment-submissions')->middleware('role:instructor')->name('tenant.instructor.assignments');

    Route::livewire('/student/courses', 'student.courses')->middleware('role:student')->name('tenant.student.courses');
    Route::livewire('/student/course/{course:slug}', 'student.course-content')->middleware('role:student')->name('tenant.student.course');
    Route::livewire('/student/quiz/{quizId}', 'student.quiz-taking')->middleware('role:student')->name('tenant.student.quiz');

    Route::get('/student/checkout/{course}', [PaymentController::class, 'checkout'])->middleware('role:student')->name('tenant.student.checkout');
    Route::post('/student/payment/process', [PaymentController::class, 'process'])->middleware('role:student')->name('tenant.student.payment.process');
    Route::get('/student/payment/confirmation/{enrollmentId}', [PaymentController::class, 'confirmation'])->middleware('role:student')->name('tenant.student.payment.confirmation');

    Route::livewire('/student/checkout-livewire/{course}', 'student.checkout')->middleware('role:student')->name('tenant.student.checkout.livewire');
    Route::livewire('/student/checkout-success/{enrollmentId?}', 'student.checkout-success')->middleware('role:student')->name('tenant.student.checkout.success');
});

// Auth routes for tenant
require __DIR__ . '/auth.php';
