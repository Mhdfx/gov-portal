<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdeaCarrierSubmission extends Model
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
        'idea_title',
        'idea_description',
        'development_level',
        'location_region',
        'location_city',
        'sector',
        'business_sector',
        'target_market',
        'innovation_description',
        'required_support',
        'pitch_deck_path',
        'supporting_documents_path',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
        'tracking_number',
        'submission_number',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
