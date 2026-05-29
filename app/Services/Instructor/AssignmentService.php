<?php

namespace App\Services\Instructor;

use App\Models\Tenant\Assignment;
use App\Models\Tenant\Section;

class AssignmentService
{
    public function findById(int $id): ?Assignment
    {
        return Assignment::find($id);
    }

    public function findWithTrashed(int $id): ?Assignment
    {
        return Assignment::withTrashed()->find($id);
    }

    public function findByIdWithRelations(int $id): ?Assignment
    {
        return Assignment::with(['attachments', 'submissions.student', 'submissions.gradedBy'])->find($id);
    }

    public function createAssignment(int $sectionId, array $data): Assignment
    {
        $section = Section::find($sectionId);

        if (! $section) {
            throw new \InvalidArgumentException('Section not found.');
        }

        $assignmentData = array_merge($data, [
            'section_id' => $sectionId,
            'course_id' => $section->course_id,
            'created_by' => auth()->id(),
        ]);

        return Assignment::create($assignmentData);
    }

    public function updateAssignment(Assignment $assignment, array $data): Assignment
    {
        $assignment->update($data);

        return $assignment;
    }

    public function softDeleteAssignment(Assignment $assignment): void
    {
        $assignment->delete();
    }

    public function restoreAssignment(Assignment $assignment): void
    {
        $assignment->restore();
    }
}
