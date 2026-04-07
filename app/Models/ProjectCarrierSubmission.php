<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectCarrierSubmission extends Model
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
        'project_name',
        'project_title',
        'project_description',
        'development_stage',
        'sector',
        'business_sector',
        'location_region',
        'location_city',
        'estimated_budget',
        'funding_required',
        'project_timeline',
        'team_size',
        'business_plan_path',
        'financial_projections_path',
        'other_documents_path',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
        'tracking_number',
        'submission_number',
    ];

    protected $casts = [
        'estimated_budget' => 'decimal:2',
        'funding_required' => 'decimal:2',
        'team_size' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
