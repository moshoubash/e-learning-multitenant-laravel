<?php

declare(strict_types=1);

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Course;
use App\Models\Tenant\Enrollment;
use Dompdf\Dompdf;

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

        $html = view('partials.certificate-pdf', [
            'course' => $course,
            'enrollment' => $enrollment,
            'user' => $user,
            'certificateId' => $certificateId,
            'completedAt' => $completedAt,
            'instructor' => $instructor,
        ])->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'certificate-' . $course->slug . '.pdf';

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
