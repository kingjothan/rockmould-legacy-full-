<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrochureDownload extends Model
{
    protected $fillable = ['project_id', 'name', 'email', 'phone', 'ip_address', 'user_agent'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
