<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'address',
        'city',
        'region',
        'postal_code',
        'country',
        'profile_type',
        'bio',
        'avatar_path',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
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
     * Check if profile is complete.
     */
    public function isComplete()
    {
        return !empty($this->first_name) && 
               !empty($this->last_name) && 
               !empty($this->phone) && 
               !empty($this->address);
    }
}
