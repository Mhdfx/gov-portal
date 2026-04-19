<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'region',
        'city',
        'province',
        'verification_status',
        'last_login_at',
        'two_factor_enabled',
        'two_factor_secret',
        'recovery_codes',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the user profile associated with the user.
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Get the company associated with the user (if role is company).
     */
    public function company()
    {
        return $this->hasOne(Company::class);
    }

    /**
     * Get all form submissions for the user.
     */
    public function investmentSubmissions()
    {
        return $this->hasMany(InvestmentSubmission::class);
    }

    public function projectCarrierSubmissions()
    {
        return $this->hasMany(ProjectCarrierSubmission::class);
    }

    public function ideaCarrierSubmissions()
    {
        return $this->hasMany(IdeaCarrierSubmission::class);
    }

    public function autoEntrepreneurSubmissions()
    {
        return $this->hasMany(AutoEntrepreneurSubmission::class);
    }

    public function indhSubmissions()
    {
        return $this->hasMany(INDHSubmission::class);
    }

    public function trainingSubmissions()
    {
        return $this->hasMany(TrainingSubmission::class);
    }

    /**
     * Get all file uploads for the user.
     */
    public function fileUploads()
    {
        return $this->hasMany(FileUpload::class);
    }

    /**
     * Get all notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get all system logs for the user.
     */
    public function systemLogs()
    {
        return $this->hasMany(SystemLog::class);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin()
    {
        return in_array($this->role, ['main_admin', 'institutional_admin', 'sectoral_admin']);
    }

    /**
     * Check if user is verified.
     */
    public function isVerified()
    {
        return $this->verification_status === 'verified';
    }

}
