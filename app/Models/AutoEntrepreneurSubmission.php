<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoEntrepreneurSubmission extends Model
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
        'business_name',
        'business_description',
        'business_type',
        'business_activity',
        'sector',
        'business_sector',
        'business_address',
        'location_region',
        'location_city',
        'startup_capital',
        'estimated_revenue',
        'start_date',
        'registration_documents_path',
        'cin_copy_path',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
        'tracking_number',
        'submission_number',
    ];

    protected $casts = [
        'estimated_revenue' => 'decimal:2',
        'start_date' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
