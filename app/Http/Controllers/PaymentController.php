<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessPaymentRequest;
use App\Models\Tenant\Course;
use App\Models\Tenant\Enrollment;
use App\Services\Student\PaymentService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;

class PaymentController extends Controller{
    use AuthorizesRequests;

    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Display the checkout page for a course.
     *
     * @param int $courseId
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function checkout($courseId)
    {
        $course = Course::find($courseId);

        if (!$course) {
            return redirect()->route('tenant.student.courses')
                ->with('error', 'Course not found.');
        }

        $this->authorize('view', $course);

        // Check if already enrolled
        $isEnrolled = Enrollment::where('course_id', $courseId)
            ->where('user_id', Auth::id())
            ->exists();

        if ($isEnrolled) {
            return redirect()->route('tenant.student.course', ['course' => $course->slug])
                ->with('info', 'You are already enrolled in this course.');
        }

        // Free courses should redirect to enrollment
        if ($course->price == 0) {
            return redirect()->route('tenant.student.courses')
                ->with('info', 'This course is free. You can enroll directly.');
        }

        return redirect()->route('tenant.student.checkout.livewire', ['course' => $course]);
    }

    /**
     * Process the payment for a course.
     *
     * @param ProcessPaymentRequest $request
     * @param PaymentService $paymentService
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function process(ProcessPaymentRequest $request, PaymentService $paymentService)
    {
        $data = $request->validated();

        $result = $paymentService->processPayment($data);

        if (isset($result['requires_action'])) {
            return response()->json([
                'success' => true,
                'requires_action' => true,
                'payment_intent_client_secret' => $result['payment_intent_client_secret'],
                'course_id' => $data['course_id'],
            ]);
        }

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'] ?? 'Payment failed. Please try again.',
            ], 400);
        }

        $enrollmentId = $result['enrollment']->id;

        return response()->json([
            'success' => true,
            'redirect_url' => route('tenant.student.payment.confirmation', ['enrollmentId' => $enrollmentId]),
        ]);
    }

    /**
     * Display the payment confirmation page.
     * Redirects to Livewire component for proper layout handling.
     *
     * @param int $enrollmentId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmation($enrollmentId)
    {
        $enrollment = Enrollment::with(['course.instructor', 'user'])
            ->find($enrollmentId);

        if (!$enrollment) {
            return redirect()->route('tenant.student.courses')
                ->with('error', 'Enrollment not found.');
        }

        $this->authorize('view', $enrollment);

        return redirect()->route('tenant.student.checkout.success', ['enrollmentId' => $enrollmentId]);
    }
}
