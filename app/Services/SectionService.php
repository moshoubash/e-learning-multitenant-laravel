<?php

namespace App\Services;

use App\Models\Tenant\Section;
use Illuminate\Support\Facades\DB;

class SectionService
{
    public function findById(int $id): ?Section
    {
        return Section::find($id);
    }

    public function findWithTrashed(int $id): ?Section
    {
        return Section::withTrashed()->find($id);
    }

    public function createSection(int $courseId, array $data): Section
    {
        return Section::create(array_merge($data, ['course_id' => $courseId]));
    }

    public function updateSection(Section $section, array $data): Section
    {
        $section->update($data);

        return $section;
    }

    public function softDeleteSection(Section $section): void
    {
        DB::transaction(function () use ($section) {
            if ($section->quiz) {
                $section->quiz->questions()->each(fn ($question) => $question->options()->delete());
                $section->quiz->questions()->delete();
                $section->quiz->delete();
            }

            $section->lessons()->delete();
            $section->delete();
        });
    }

    public function restoreSection(Section $section): void
    {
        $section->restore();
    }
}
