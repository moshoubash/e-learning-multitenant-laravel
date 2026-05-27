<?php

namespace App\Livewire\Student;

use App\Models\Tenant\Course;
use App\Models\Tenant\Enrollment;
use App\Services\Student\PaymentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class Checkout extends Component
{
    public Course $course;
    public ?string $paymentMethodId = null;
    public bool $isProcessing = false;
    public ?string $errorMessage = null;
    public bool $requiresAction = false;
    public ?string $clientSecret = null;

    protected ?PaymentService $paymentService = null;

    public function mount(Course $course)
    {
        $this->course = $course;

        // Check if already enrolled
        $isEnrolled = Enrollment::where('course_id', $course->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($isEnrolled) {
            return redirect()->route('tenant.student.course', ['course' => $course->slug])
                ->with('info', 'You are already enrolled in this course.');
        }

        // Free courses should not be here
        if ($course->price == 0) {
            return redirect()->route('tenant.student.courses')
                ->with('info', 'This course is free. You can enroll directly.');
        }

        $this->paymentService = app(PaymentService::class);
    }

    /**
     * Listen for processPayment event from JavaScript
     */
    #[On('processPayment')]
    public function handleProcessPaymentEvent($paymentMethodId): void
    {
        $this->processPayment($paymentMethodId);
    }

    /**
     * Handle the Stripe payment confirmation result.
     */
    public function handlePaymentResult(array $result): void
    {
        if (isset($result['requires_action']) && $result['requires_action']) {
            $this->requiresAction = true;
            $this->clientSecret = $result['payment_intent_client_secret'];
            $this->isProcessing = false;
            return;
        }

        if (!$result['success']) {
            $this->errorMessage = $result['error'] ?? 'Payment failed. Please try again.';
            $this->isProcessing = false;
            return;
        }

        // Payment successful - redirect to confirmation
        $enrollmentId = $result['enrollment']->id;
        $this->isProcessing = false;

        $this->redirect(route('tenant.student.payment.confirmation', ['enrollmentId' => $enrollmentId]));
    }

    /**
     * Process the payment using the provided payment method.
     */
    public function processPayment(string $paymentMethodId): void
    {
        $this->isProcessing = true;
        $this->errorMessage = null;

        // Initialize payment service if not already done
        if ($this->paymentService === null) {
            $this->paymentService = app(PaymentService::class);
        }

        $result = $this->paymentService->processPayment([
            'course_id' => $this->course->id,
            'payment_method_id' => $paymentMethodId,
        ]);

        $this->handlePaymentResult($result);
    }

    /**
     * Handle payment confirmation after 3D Secure.
     */
    public function confirmPayment(): void
    {
        if (!$this->clientSecret) {
            $this->errorMessage = 'Payment confirmation failed. Please try again.';
            return;
        }

        // Initialize payment service if not already done
        if ($this->paymentService === null) {
            $this->paymentService = app(PaymentService::class);
        }

        // Extract payment intent ID from client secret
        $paymentIntentId = str_replace('_secret_', '', explode('_secret_', $this->clientSecret)[0]);

        $result = $this->paymentService->confirmPayment($paymentIntentId);

        if (!$result['success']) {
            $this->errorMessage = $result['error'] ?? 'Payment confirmation failed.';
            $this->isProcessing = false;
            return;
        }

        if ($result['status'] === 'succeeded') {
            // Payment confirmed, create enrollment
            $enrollment = Enrollment::create([
                'user_id' => Auth::id(),
                'course_id' => $this->course->id,
                'enrolled_at' => now(),
                'status' => 'active',
            ]);

            $this->isProcessing = false;
            $this->redirect(route('tenant.student.payment.confirmation', ['enrollmentId' => $enrollment->id]));
        }

        $this->errorMessage = 'Payment was not completed. Please try again.';
        $this->isProcessing = false;
    }

    /**
     * Retry payment with a different method.
     */
    public function retryPayment(): void
    {
        $this->requiresAction = false;
        $this->clientSecret = null;
        $this->errorMessage = null;
    }

    public function render()
    {
        return view('livewire.student.checkout');
    }
}
