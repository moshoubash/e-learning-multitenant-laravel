<?php

namespace App\Services\Instructor;

use App\Models\Tenant\Lesson;
use App\Models\Tenant\Section;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yaza\LaravelGoogleDriveStorage\Gdrive;
use Masmerise\Toaster\Toaster;


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
            $videoUrl = $this->uploadVideoToGoogleDrive($video);

            $getID3 = new \getID3();
            $fileInfo = $getID3->analyze($video->getRealPath());
            $data['duration_seconds'] = (int) round($fileInfo['playtime_seconds'] ?? 0);
            $data['video_url'] = $videoUrl;
        }

        return Lesson::create(array_merge($data, ['section_id' => $sectionId]));
    }

    public function updateLesson(Lesson $lesson, array $data, $video = null): Lesson
    {
        if ($video) {
            $data['video_url'] = $this->uploadVideoToGoogleDrive($video);

            $getID3 = new \getID3();
            $fileInfo = $getID3->analyze($video->getRealPath());
            $data['duration_seconds'] = (int) round($fileInfo['playtime_seconds'] ?? 0);
        }

        if (array_key_exists('video_url', $data) && ! $data['video_url']) {
            $data['video_url'] = null;
        }

        $lesson->update($data);

        return $lesson;
    }

    private function uploadVideoToGoogleDrive($video): string
    {
        $tenantId = tenant('id') ?? 'default';
        $path = "courses/{$tenantId}/".Str::random(40).'.'.$video->extension();

        Gdrive::put($path, $video->getRealPath());

        $url = Storage::disk('google')->getAdapter()->getUrl($path);

        if (! $url) {
            Toaster::error("Failed to generate a public URL for the Google Drive video '{$path}'. Make sure the video was uploaded and is shareable.");
        }

        if (preg_match('/(?:\/d\/|id=)([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://drive.google.com/file/d/' . $matches[1] . '/preview';
        }

        return $url;
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
