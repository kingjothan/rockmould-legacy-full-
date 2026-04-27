<?php

namespace App\Models;

use App\Support\AssetPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'benefits' => 'array',
            'process_steps' => 'array',
            'related_projects' => 'array',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function getImageUrlAttribute(): string
    {
        return AssetPath::url($this->image_path, 'images/project-1.jpg');
    }
}
