<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'code',
        'description',
        'email',
        'phone',
        'address',
        'city',
        'region',
        'postal_code',
        'country',
        'jurisdiction',
        'admin_user_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'jurisdiction' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the admin user assigned to this institution.
     */
    public function adminUser()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    /**
     * Get all form routing rules for this institution.
     */
    public function formRoutingRules()
    {
        return $this->hasMany(FormRoutingRule::class);
    }

    /**
     * Get all submission routing logs for this institution.
     */
    public function submissionRoutingLogs()
    {
        return $this->hasMany(SubmissionRoutingLog::class);
    }

    /**
     * Check if institution is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if institution has admin assigned.
     */
    public function hasAdmin()
    {
        return !is_null($this->admin_user_id);
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
            $address .= ' ' . $this->postal_code;
        }
        if ($this->country && $this->country !== 'Morocco') {
            $address .= ', ' . $this->country;
        }
        return $address;
    }

    /**
     * Check if institution has jurisdiction over a specific region.
     */
    public function hasJurisdictionOverRegion($region)
    {
        if (!$this->jurisdiction || !isset($this->jurisdiction['regions'])) {
            return false;
        }
        
        return in_array($region, $this->jurisdiction['regions']);
    }

    /**
     * Check if institution has jurisdiction over a specific sector.
     */
    public function hasJurisdictionOverSector($sector)
    {
        if (!$this->jurisdiction || !isset($this->jurisdiction['sectors'])) {
            return false;
        }
        
        return in_array($sector, $this->jurisdiction['sectors']);
    }

    /**
     * Scope for active institutions.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for institutions by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for institutions by region.
     */
    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }
}
