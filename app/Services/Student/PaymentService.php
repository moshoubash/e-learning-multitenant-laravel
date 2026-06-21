<?php

namespace App\Services\Student;

use App\Models\Tenant\Course;
use App\Models\Tenant\Enrollment;
use App\Notifications\EnrollmentConfirmed;
use App\Notifications\NewEnrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Process the payment with Stripe and create enrollment records.
     *
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function processPayment(array $data)
    {
        try {
            $course = Course::find($data['course_id']);

            if (!$course) {
                throw new \Exception('Course not found.');
            }

            // Process Stripe payment
            $paymentIntent = PaymentIntent::create([
                'amount' => intval($course->price * 100),
                'currency' => 'usd',
                'payment_method' => $data['payment_method_id'],
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                ],
                'confirm' => true
            ]);

            // Check if payment requires additional action (3D Secure)
            if ($paymentIntent->status === 'requires_action' && $paymentIntent->next_action->type === 'use_stripe_sdk') {
                return [
                    'requires_action' => true,
                    'payment_intent_client_secret' => $paymentIntent->client_secret
                ];
            }

            // Use transaction to ensure data consistency
            $result = DB::transaction(function () use ($course, $paymentIntent) {
                // Create enrollment
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

                // Transaction::create([...]);

                return [
                    'success' => true,
                    'enrollment' => $enrollment,
                    'payment_intent_id' => $paymentIntent->id,
                ];
            });

            return $result;

        } catch (\Stripe\Exception\CardException $e) {
            return [
                'success' => false,
                'error' => 'Card declined: ' . $e->getMessage(),
                'error_code' => $e->getStripeCode(),
            ];
        } catch (\Stripe\Exception\RateLimitException $e) {
            return [
                'success' => false,
                'error' => 'Too many requests. Please try again.',
                'error_code' => 'rate_limit',
            ];
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return [
                'success' => false,
                'error' => 'Invalid request. Please check your payment details.',
                'error_code' => 'invalid_request',
            ];
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return [
                'success' => false,
                'error' => 'Payment service authentication failed. Please contact support.',
                'error_code' => 'authentication_failed',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Payment failed: ' . $e->getMessage(),
                'error_code' => 'unknown_error',
            ];
        }
    }

    /**
     * Handle payment confirmation after 3D Secure authentication
     *
     * @param string $paymentIntentId
     * @return array
     */
    public function confirmPayment(string $paymentIntentId)
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            if ($paymentIntent->status === 'succeeded') {
                return [
                    'success' => true,
                    'status' => 'succeeded',
                ];
            }

            if ($paymentIntent->status === 'requires_payment_method') {
                return [
                    'success' => false,
                    'error' => 'Payment was not completed. Please try again.',
                    'status' => $paymentIntent->status,
                ];
            }

            return [
                'success' => true,
                'status' => $paymentIntent->status,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to confirm payment: ' . $e->getMessage(),
            ];
        }
    }
}
