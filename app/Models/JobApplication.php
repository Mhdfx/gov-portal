<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'job_listing_id',
        'cover_letter',
        'cv_file_path',
        'additional_documents_path',
        'status',
        'admin_notes',
        'applied_at',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the candidate that owns the job application.
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Get the job listing for this application.
     */
    public function jobListing(): BelongsTo
    {
        return $this->belongsTo(JobListing::class);
    }

    /**
     * Get the user who reviewed this application.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the CV file URL.
     */
    public function getCvUrlAttribute(): ?string
    {
        return $this->cv_file_path ? asset('storage/' . $this->cv_file_path) : null;
    }

    /**
     * Get the additional documents URL.
     */
    public function getAdditionalDocumentsUrlAttribute(): ?string
    {
        return $this->additional_documents_path ? asset('storage/' . $this->additional_documents_path) : null;
    }

    /**
     * Scope to get applications by status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get recent applications.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('applied_at', '>=', now()->subDays($days));
    }
}