<?php

declare(strict_types=1);

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Course;
use App\Models\Tenant\Enrollment;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function download(Course $course)
    {
        $enrollment = Enrollment::where('course_id', $course->id)
            ->where('user_id', auth()->id())
            ->where('status', Enrollment::STATUS_COMPLETED)
            ->firstOrFail();

        $user = auth()->user();

        $certificateId = 'CERT-' . str_pad((string) $course->id, 4, '0', STR_PAD_LEFT)
            . '-' . str_pad((string) $enrollment->id, 4, '0', STR_PAD_LEFT);

        $completedAt = $enrollment->completed_at
            ? $enrollment->completed_at->format('F d, Y')
            : now()->format('F d, Y');

        $instructor = $course->instructor;

        $pdf = Pdf::loadView('partials.certificate-pdf', [
            'course' => $course,
            'enrollment' => $enrollment,
            'user' => $user,
            'certificateId' => $certificateId,
            'completedAt' => $completedAt,
            'instructor' => $instructor,
        ]);

        $filename = 'certificate-' . $course->slug . '.pdf';

        return $pdf->download($filename);
    }
}
