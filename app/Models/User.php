<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_active_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(ProjectAssignment::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class, 'client_id');
    }

    public function projects(): HasManyThrough
    {
        return $this->hasManyThrough(Project::class, ProjectAssignment::class, 'user_id', 'id', 'id', 'project_id');
    }

    public function otps(): HasMany
    {
        return $this->hasMany(ClientOtp::class);
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function uploadedDocuments(): HasMany
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }

    public function uploadedMedia(): HasMany
    {
        return $this->hasMany(MediaAsset::class, 'uploaded_by');
    }

    public function reviewedKycs(): HasMany
    {
        return $this->hasMany(KycRequest::class, 'reviewed_by');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function isAdminRole(): bool
    {
        return in_array($this->role, ['admin', 'editor', 'project_manager', 'finance', 'content_manager'], true);
    }
}
