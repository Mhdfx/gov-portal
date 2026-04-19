<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'slug',
        'company_type',
        'registration_number',
        'tax_number',
        'description',
        'website',
        'phone',
        'email',
        'address',
        'city',
        'region',
        'postal_code',
        'country',
        'logo',
        'cover_banner',
        'business_sectors',
        'employee_count',
        'annual_revenue',
        'social_links',
        'approval_status',
        'admin_notes',
        'approved_at',
        'approved_by',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'business_sectors' => 'array',
            'social_links' => 'array',
            'annual_revenue' => 'float',
            'approved_at' => 'datetime',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($company) {
            if (empty($company->slug)) {
                $company->slug = \Illuminate\Support\Str::slug($company->company_name) . '-' . \Illuminate\Support\Str::random(5);
            }
        });
    }

    /**
     * Get the updates for this company.
     */
    public function updates()
    {
        return $this->hasMany(CompanyUpdate::class);
    }

    /**
     * Get the user that owns the company.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who approved the company.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get all company documents.
     */
    public function documents()
    {
        return $this->hasMany(CompanyDocument::class);
    }

    /**
     * Get all products for this company.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get all orders for this company.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all job listings for this company.
     */
    public function jobListings()
    {
        return $this->hasMany(JobListing::class);
    }

    /**
     * Check if company is approved.
     */
    public function isApproved()
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Check if company is pending approval.
     */
    public function isPending()
    {
        return $this->approval_status === 'pending';
    }

    /**
     * Check if company is rejected.
     */
    public function isRejected()
    {
        return $this->approval_status === 'rejected';
    }

    /**
     * Scope a query to only include approved companies.
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Scope a query to only include active companies.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the formatted address attribute.
     */
    public function getFormattedAddressAttribute()
    {
        $address = $this->address;
        if ($this->city) {
            $address .= ', ' . $this->city;
        }
        if ($this->region) {
            $address .= ', ' . $this->region;
        }
        if ($this->postal_code) {
            $address .= ', ' . $this->postal_code;
        }
        if ($this->country && $this->country !== 'Morocco') {
            $address .= ', ' . $this->country;
        }
        return $address;
    }

    /**
     * Scope for approved companies.
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Scope for active companies.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for companies by region.
     */
    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }
}
