<?php

namespace App\Models;

use App\Support\AssetPath;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'highlights' => 'array',
            'gallery' => 'array',
            'floor_plans' => 'array',
            'documents' => 'array',
            'launch_date' => 'date',
            'completion_date' => 'date',
            'is_featured' => 'boolean',
            'is_portfolio' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(ProjectAssignment::class);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(ProjectMilestone::class)->orderBy('sort_order')->orderBy('milestone_date');
    }

    public function updates(): HasMany
    {
        return $this->hasMany(ProjectUpdate::class)->latest('update_date');
    }

    public function documentsRelation(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function mediaAssets(): HasMany
    {
        return $this->hasMany(MediaAsset::class)->orderBy('sort_order');
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class)->orderBy('sort_order');
    }

    public function getCoverImageUrlAttribute(): string
    {
        return AssetPath::url($this->cover_image, 'images/project-1.jpg');
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }
}
