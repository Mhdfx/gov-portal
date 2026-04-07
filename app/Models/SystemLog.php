<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'log_data',
        'level',
    ];

    protected $casts = [
        'log_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
