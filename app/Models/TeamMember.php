<?php

namespace App\Models;

use App\Support\AssetPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }

    public function getImageUrlAttribute(): ?string
    {
        return AssetPath::url($this->image_path);
    }
}
