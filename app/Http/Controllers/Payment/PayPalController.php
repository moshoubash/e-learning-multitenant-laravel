<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Course;
use App\Models\Tenant\Enrollment;
use App\Notifications\EnrollmentConfirmed;
use App\Notifications\NewEnrollment;
use App\Services\Student\PayPalService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayPalController extends Controller
{
    use AuthorizesRequests;

    protected PayPalService $payPalService;

    public function __construct(PayPalService $payPalService)
    {
        $this->payPalService = $payPalService;
    }

    public function create($courseId)
    {
        $course = Course::find($courseId);

        if (!$course) {
            return redirect()->route('tenant.student.courses')
                ->with('error', 'Course not found.');
        }

        $this->authorize('view', $course);

        if ($course->price == 0) {
            return redirect()->route('tenant.student.courses')
                ->with('info', 'This course is free.');
        }

        $isEnrolled = Enrollment::where('course_id', $courseId)
            ->where('user_id', Auth::id())
            ->exists();

        if ($isEnrolled) {
            return redirect()->route('tenant.student.course', ['course' => $course->slug])
                ->with('info', 'You are already enrolled in this course.');
        }

        if (!$this->payPalService->isConfigured()) {
            return redirect()->route('tenant.student.checkout', ['course' => $courseId])
                ->with('error', 'PayPal is not configured. Please try another payment method.');
        }

        $returnUrl = route('tenant.student.paypal.success', ['course' => $courseId]);
        $cancelUrl = route('tenant.student.paypal.cancel', ['course' => $courseId]);

        $result = $this->payPalService->createOrder($course->price, $returnUrl, $cancelUrl);

        if (!$result['success'] || !$result['approval_url']) {
            return redirect()->route('tenant.student.checkout', ['course' => $courseId])
                ->with('error', $result['error'] ?? 'Failed to initiate PayPal payment.');
        }

        session()->put('paypal_order_id', $result['order_id']);
        session()->put('paypal_course_id', $courseId);

        return redirect()->away($result['approval_url']);
    }

    public function success(Request $request, $courseId)
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect()->route('tenant.student.checkout', ['course' => $courseId])
                ->with('error', 'Invalid PayPal response. Missing payment token.');
        }

        $orderId = session()->pull('paypal_order_id', $token);

        $result = $this->payPalService->captureOrder($orderId);

        if (!$result['success']) {
            return redirect()->route('tenant.student.checkout', ['course' => $courseId])
                ->with('error', $result['error'] ?? 'PayPal payment capture failed.');
        }

        $course = Course::find($courseId);

        if (!$course) {
            return redirect()->route('tenant.student.courses')
                ->with('error', 'Course not found.');
        }

        $enrollment = Enrollment::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'status' => Enrollment::STATUS_ACTIVE,
        ]);

        $course->load('instructor');
        $student = Auth::user();
        if ($course->instructor) {
            $course->instructor->notify(new NewEnrollment($student, $course));
        }
        if ($student) {
            $student->notify(new EnrollmentConfirmed($course));
        }

        session()->forget(['paypal_order_id', 'paypal_course_id']);

        return redirect()->route('tenant.student.payment.confirmation', ['enrollmentId' => $enrollment->id]);
    }

    public function cancel($courseId)
    {
        session()->forget(['paypal_order_id', 'paypal_course_id']);

        return redirect()->route('tenant.student.checkout', ['course' => $courseId])
            ->with('error', 'PayPal payment was cancelled. Please try again.');
    }
}
