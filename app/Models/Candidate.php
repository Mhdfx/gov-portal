<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'nationality',
        'address',
        'city',
        'region',
        'postal_code',
        'education_level',
        'field_of_study',
        'university',
        'years_of_experience',
        'skills',
        'languages',
        'professional_summary',
        'cv_file_path',
        'cover_letter_path',
        'profile_picture_path',
        'availability',
        'expected_salary',
        'preferred_job_type',
        'preferred_locations',
        'preferred_sectors',
        'is_available',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'skills' => 'array',
        'languages' => 'array',
        'preferred_locations' => 'array',
        'preferred_sectors' => 'array',
        'is_available' => 'boolean',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'expected_salary' => 'decimal:2',
    ];

    /**
     * Get the user that owns the candidate profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the job applications for the candidate.
     */
    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * Get the full name of the candidate.
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the age of the candidate.
     */
    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }

    /**
     * Scope to get available candidates.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope to get verified candidates.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope to filter by region.
     */
    public function scopeInRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    /**
     * Scope to filter by education level.
     */
    public function scopeWithEducationLevel($query, $level)
    {
        return $query->where('education_level', $level);
    }

    /**
     * Scope to filter by experience years.
     */
    public function scopeWithExperience($query, $minYears)
    {
        return $query->where('years_of_experience', '>=', $minYears);
    }

    /**
     * Check if candidate has a specific skill.
     */
    public function hasSkill($skill): bool
    {
        return in_array($skill, $this->skills ?? []);
    }

    /**
     * Check if candidate speaks a specific language.
     */
    public function speaksLanguage($language): bool
    {
        return in_array($language, $this->languages ?? []);
    }

    /**
     * Get the CV file URL.
     */
    public function getCvUrlAttribute(): ?string
    {
        return $this->cv_file_path ? asset('storage/' . $this->cv_file_path) : null;
    }

    /**
     * Get the cover letter file URL.
     */
    public function getCoverLetterUrlAttribute(): ?string
    {
        return $this->cover_letter_path ? asset('storage/' . $this->cover_letter_path) : null;
    }

    /**
     * Get the profile picture URL.
     */
    public function getProfilePictureUrlAttribute(): ?string
    {
        return $this->profile_picture_path ? asset('storage/' . $this->profile_picture_path) : null;
    }
}