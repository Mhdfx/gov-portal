<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'content',
        'image_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the company that owns the update.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
