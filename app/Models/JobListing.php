<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'requirements',
        'responsibilities',
        'job_type',
        'employment_type',
        'experience_level',
        'education_required',
        'required_skills',
        'preferred_skills',
        'location',
        'city',
        'region',
        'is_remote',
        'salary_min',
        'salary_max',
        'salary_currency',
        'currency',
        'benefits',
        'application_deadline',
        'start_date',
        'status',
        'views_count',
        'applications_count',
        'is_featured',
        'featured_until',
        'is_active',
    ];

    protected $casts = [
        'required_skills' => 'array',
        'preferred_skills' => 'array',
        'is_remote' => 'boolean',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'application_deadline' => 'date',
        'start_date' => 'date',
        'is_featured' => 'boolean',
        'featured_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the company that owns the job listing.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the job applications for this listing.
     */
    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * Get the salary range as a formatted string.
     */
    public function getSalaryRangeAttribute(): string
    {
        if ($this->salary_min && $this->salary_max) {
            return number_format($this->salary_min) . ' - ' . number_format($this->salary_max) . ' ' . $this->salary_currency;
        } elseif ($this->salary_min) {
            return 'À partir de ' . number_format($this->salary_min) . ' ' . $this->salary_currency;
        } elseif ($this->salary_max) {
            return 'Jusqu\'à ' . number_format($this->salary_max) . ' ' . $this->salary_currency;
        }
        
        return 'Salaire à négocier';
    }

    /**
     * Check if the job listing is still accepting applications.
     */
    public function isAcceptingApplications(): bool
    {
        return $this->status === 'active' && $this->application_deadline >= now()->toDateString();
    }

    /**
     * Check if the job listing is featured.
     */
    public function isFeatured(): bool
    {
        return $this->is_featured && (!$this->featured_until || $this->featured_until > now());
    }

    /**
     * Scope to get active job listings.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('application_deadline', '>=', now()->toDateString());
    }

    /**
     * Scope to get featured job listings.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)
                    ->where(function($q) {
                        $q->whereNull('featured_until')
                          ->orWhere('featured_until', '>', now());
                    });
    }

    /**
     * Scope to filter by region.
     */
    public function scopeInRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    /**
     * Scope to filter by job type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('job_type', $type);
    }

    /**
     * Scope to filter by experience level.
     */
    public function scopeWithExperienceLevel($query, $level)
    {
        return $query->where('experience_level', $level);
    }

    /**
     * Scope to search job listings.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('location', 'like', "%{$search}%");
        });
    }

    /**
     * Increment the views count.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Increment the applications count.
     */
    public function incrementApplications(): void
    {
        $this->increment('applications_count');
    }
}