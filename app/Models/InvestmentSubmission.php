<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentSubmission extends Model
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
        'project_description',
        'contact_person',
        'contact_email',
        'contact_phone',
        'investment_amount',
        'investment_type',
        'sector',
        'business_sector',
        'business_plan_summary',
        'expected_roi',
        'timeline',
        'business_plan_path',
        'financial_statements_path',
        'other_documents_path',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
        'tracking_number',
        'submission_number',
        'submitted_at',
    ];

    protected $casts = [
        'investment_amount' => 'decimal:2',
        'expected_roi' => 'decimal:2',
        'reviewed_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
