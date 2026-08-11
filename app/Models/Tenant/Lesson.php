<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'section_id',
        'title',
        'type',
        'content',
        'duration_seconds',
        'order',
        'video_url',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function lessonProgress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function getIsYoutubeUrlAttribute(): bool
    {
        if (!$this->video_url) {
            return false;
        }

        return preg_match('/(youtube\.com|youtu\.be)/', $this->video_url) === 1;
    }

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if (!$this->video_url) {
            return null;
        }

        preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $this->video_url, $matches);

        return $matches[1] ?? null
            ? 'https://www.youtube.com/embed/' . $matches[1]
            : null;
    }

    public function getIsGoogleDriveUrlAttribute(): bool
    {
        if (!$this->video_url) {
            return false;
        }

        return preg_match('/drive\.google\.com/', $this->video_url) === 1;
    }

    public function getGoogleDriveEmbedUrlAttribute(): ?string
    {
        if (!$this->video_url) {
            return 'https://drive.google.com/file/d/1wg4e2qsEl9C09DvolULmCa7wCLJJrJ85/preview';
        }

        if (preg_match('/(?:\/d\/|id=)([a-zA-Z0-9_-]+)/', $this->video_url, $matches)) {
            return 'https://drive.google.com/file/d/' . $matches[1] . '/preview';
        }

        return 'https://drive.google.com/file/d/1wg4e2qsEl9C09DvolULmCa7wCLJJrJ85/preview';
    }
}
