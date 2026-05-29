<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant\Course;
use App\Models\Tenant\Section;
use App\Models\Tenant\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'section_id',
        'title',
        'description',
        'instructions',
        'due_date',
        'max_score',
        'allow_late',
        'status',
        'created_by',
        'order',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'allow_late' => 'boolean',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(AssignmentAttachment::class);
    }
}
