<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Payment\PayPalController;

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

// Route::get('/', function(){
//     return redirect()->route('tenant.dashboard');
// });
Route::get('/', \App\Livewire\Landing::class)->name('landing.home');

Route::fallback(function () {
    return view('errors.404');
})->name('tenant.fallback');

Route::middleware(['auth:tenant'])->group(function () {
    Route::get('/dashboard', \App\Livewire\Dashboard::class)->name('tenant.dashboard');
    Route::get('/notifications', \App\Livewire\Notifications::class)->name('tenant.notifications');

    Route::get('/profile', \App\Livewire\Profile::class)->name('tenant.profile');

    Route::livewire('/admin/users', 'admin.users')->middleware(['role:admin', 'permission:view users|create users|edit users|delete users'])->name('tenant.admin.users');
    Route::livewire('/admin/courses', 'admin.courses')->middleware(['role:admin', 'permission:view courses|create courses|edit courses|delete courses'])->name('tenant.admin.courses');
    Route::livewire('/admin/quizzes', 'admin.quizzes')->middleware(['role:admin', 'permission:view quizzes|create quizzes|edit quizzes|delete quizzes'])->name('tenant.admin.quizzes');
    Route::livewire('/admin/enrollments', 'admin.enrollments')->middleware(['role:admin', 'permission:view enrollments|create enrollments|edit enrollments|delete enrollments'])->name('tenant.admin.enrollments');
    Route::livewire('/admin/integrations', 'admin.integrations')->middleware('role:admin')->name('tenant.admin.integrations');
    Route::livewire('/admin/design', 'admin.design-config')->middleware('role:admin')->name('tenant.admin.design');
    Route::get('/admin/logs', \App\Livewire\Admin\Logs::class)->middleware('role:admin')->name('tenant.admin.logs');
    Route::get('/admin/roles-permissions', \App\Livewire\Admin\RolesAndPermissions::class)->middleware('role:admin')->name('tenant.admin.roles-permissions');
    Route::get('/admin/smtp-settings', \App\Livewire\Admin\SmtpSettings::class)->middleware('role:admin')->name('tenant.admin.smtp-settings');
    Route::get('/admin/leaderboard', \App\Livewire\Admin\LeaderboardMonitor::class)->middleware('role:admin')->name('tenant.admin.leaderboard');

    Route::livewire('/instructor/courses', 'instructor.courses')->middleware(['role:instructor', 'permission:view courses|create courses|edit courses|delete courses'])->name('tenant.instructor.courses');
    Route::livewire('/instructor/quizzes', 'instructor.quizzes')->middleware(['role:instructor', 'permission:view quizzes|create quizzes|edit quizzes|delete quizzes'])->name('tenant.instructor.quizzes');
    Route::livewire('/instructor/assignments', 'instructor.assignment-submissions')->middleware('role:instructor')->name('tenant.instructor.assignments');
    Route::get('/instructor/enrollments', \App\Livewire\Instructor\Enrollments::class)->middleware(['role:instructor', 'permission:view enrollments'])->name('tenant.instructor.enrollments');

    Route::livewire('/student/courses', 'student.courses')->middleware(['role:student', 'permission:view courses'])->name('tenant.student.courses');
    Route::livewire('/student/enrolled-courses', 'student.enrolled-courses')->middleware(['role:student', 'permission:view courses'])->name('tenant.student.enrolled-courses');
    Route::livewire('/student/course/{course:slug}', 'student.course-content')->middleware(['role:student', 'permission:view courses|view sections|view lessons'])->name('tenant.student.course');
    Route::livewire('/student/quiz/{quizId}', 'student.quiz-taking')->middleware(['role:student', 'permission:view quizzes|take quizzes'])->name('tenant.student.quiz');

    Route::get('/student/checkout/{course}', [PaymentController::class, 'checkout'])->middleware(['role:student', 'permission:view courses|create enrollments'])->name('tenant.student.checkout');
    Route::post('/student/payment/process', [PaymentController::class, 'process'])->middleware(['role:student', 'permission:view courses|create enrollments'])->name('tenant.student.payment.process');
    Route::get('/student/payment/confirmation/{enrollmentId}', [PaymentController::class, 'confirmation'])->middleware(['role:student', 'permission:view courses'])->name('tenant.student.payment.confirmation');

    Route::post('/student/paypal/create/{course}', [PayPalController::class, 'create'])->middleware(['role:student', 'permission:view courses|create enrollments'])->name('tenant.student.paypal.create');
    Route::get('/student/paypal/success/{course}', [PayPalController::class, 'success'])->middleware(['role:student', 'permission:view courses|create enrollments'])->name('tenant.student.paypal.success');
    Route::get('/student/paypal/cancel/{course}', [PayPalController::class, 'cancel'])->middleware(['role:student', 'permission:view courses|create enrollments'])->name('tenant.student.paypal.cancel');

    Route::livewire('/student/leaderboard', 'student.leaderboard')->middleware('role:student')->name('tenant.student.leaderboard');

    Route::livewire('/student/certificate/{course:slug}', 'student.certificate')->middleware(['role:student', 'permission:view courses|view own progress'])->name('tenant.student.certificate');
    Route::get('/student/certificate/{course:slug}/download', [\App\Http\Controllers\Student\CertificateController::class, 'download'])->middleware(['role:student', 'permission:view courses|view own progress'])->name('tenant.student.certificate.download');

    Route::livewire('/student/checkout-livewire/{course}', 'student.checkout')->middleware(['role:student', 'permission:view courses|create enrollments'])->name('tenant.student.checkout.livewire');
    Route::livewire('/student/checkout-success/{enrollmentId?}', 'student.checkout-success')->middleware(['role:student', 'permission:view courses'])->name('tenant.student.checkout.success');
    Route::get('/student/enrollments-history', \App\Livewire\Student\EnrollmentsHistory::class)->middleware(['role:student', 'permission:view courses|view own progress'])->name('tenant.student.enrollments-history');
});

// Auth routes for tenant
require __DIR__ . '/auth.php';
