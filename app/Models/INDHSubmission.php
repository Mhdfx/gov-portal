<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class INDHSubmission extends Model
{
    use HasFactory;

    protected $table = 'indh_submissions';

    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone',
        'cin',
        'city',
        'region',
        'province',
        'project_title',
        'project_description',
        'project_type',
        'community_impact',
        'target_beneficiaries',
        'beneficiary_type',
        'number_of_beneficiaries',
        'location_region',
        'location_city',
        'estimated_budget',
        'requested_funding',
        'funding_required',
        'project_location',
        'project_duration',
        'project_duration_months',
        'expected_impact',
        'project_proposal_path',
        'budget_breakdown_path',
        'supporting_documents_path',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
        'tracking_number',
        'submission_number',
    ];

    protected $casts = [
        'number_of_beneficiaries' => 'integer',
        'estimated_budget' => 'decimal:2',
        'requested_funding' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
