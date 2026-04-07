<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_name',
        'original_name',
        'stored_name',
        'file_path',
        'file_type',
        'upload_type',
        'file_size',
        'mime_type',
        'uploadable_type',
        'uploadable_id',
        'description',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function uploadable()
    {
        return $this->morphTo();
    }
}
