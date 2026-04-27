<?php

namespace App\Models;

use App\Support\AssetPath;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function getCoverImageUrlAttribute(): string
    {
        return AssetPath::url($this->cover_image, 'images/project-1.jpg');
    }

    public function scopePublished(Builder $query): void
    {
        $query
            ->where('is_published', true)
            ->where(function (Builder $builder) {
                $builder->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }
}
