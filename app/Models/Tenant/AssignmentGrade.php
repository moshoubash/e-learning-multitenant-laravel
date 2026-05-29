<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_submission_id',
        'score',
        'feedback',
        'graded_by',
        'graded_at',
        'status',
    ];

    protected $casts = [
        'graded_at' => 'datetime',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(AssignmentSubmission::class, 'assignment_submission_id');
    }

    public function gradedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
