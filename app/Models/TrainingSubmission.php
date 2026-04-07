<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone',
        'cin',
        'city',
        'region',
        'province',
        'training_title',
        'training_description',
        'training_type',
        'training_domain',
        'target_audience',
        'participant_count',
        'duration_hours',
        'current_skill_level',
        'desired_skill_level',
        'preferred_schedule',
        'education_level',
        'work_experience',
        'employment_status',
        'cv_path',
        'motivation_letter_path',
        'certificates_path',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
        'tracking_number',
        'submission_number',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
