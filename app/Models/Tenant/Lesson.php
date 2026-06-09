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
        'is_free_preview',
        'order',
        'video_url',
    ];

    protected function casts(): array
    {
        return [
            'is_free_preview' => 'boolean',
            'duration_seconds' => 'integer',
            'order' => 'integer',
        ];
    }

    public function isPreview(): bool
    {
        return $this->is_free_preview === true;
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function lessonProgress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }
}
