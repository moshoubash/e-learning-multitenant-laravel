<?php

namespace App\Services\Instructor;

use App\Models\Tenant\Lesson;
use App\Models\Tenant\Section;
use Illuminate\Support\Facades\Storage;

class LessonService
{
    public function createLesson(int $sectionId, array $data, $video = null): Lesson
    {
        $section = Section::find($sectionId);

        if (! $section) {
            throw new \InvalidArgumentException('Section not found.');
        }

        $videoUrl = null;

        if ($video) {
            $tenantId = tenant('id') ?? 'default';
            $baseUrl = 'https://d1w6oovjx4x1vx.cloudfront.net';
            $path = $video->storeAs("courses/{$tenantId}", rand() . time() . $video->getClientOriginalName(), 's3');
            $videoUrl = $baseUrl . '/' . $path;

            $getID3 = new \getID3();
            $fileInfo = $getID3->analyze($video->getRealPath());
            $data['duration_seconds'] = (int) round($fileInfo['playtime_seconds'] ?? 0);
            $data['video_url'] = $videoUrl;
        }

        return Lesson::create(array_merge($data, ['section_id' => $sectionId]));
    }

    public function updateLesson(Lesson $lesson, array $data): Lesson
    {
        if (array_key_exists('video_url', $data) && ! $data['video_url']) {
            $data['video_url'] = null;
        }

        $lesson->update($data);

        return $lesson;
    }

    public function softDeleteLesson(Lesson $lesson): void
    {
        $lesson->delete();
    }

    public function restoreLesson(Lesson $lesson): void
    {
        $lesson->restore();
    }

    public function findById(int $id): ?Lesson
    {
        return Lesson::find($id);
    }

    public function findWithTrashed(int $id): ?Lesson
    {
        return Lesson::withTrashed()->find($id);
    }
}
