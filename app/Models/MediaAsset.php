<?php

namespace App\Models;

use App\Support\AssetPath;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MediaAsset extends Model
{
    use HasFactory;

    protected static ?bool $gallerySectionsSupported = null;

    public const SECTION_EXTERIOR = 'exterior';

    public const SECTION_INTERIOR = 'interior';

    public const SECTION_GENERAL = 'general';

    public const SECTION_LIVE_UPDATES = 'live-updates';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'visible_to_client' => 'boolean',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(ProjectAssignment::class, 'project_assignment_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function scopeVisibleToPortalUser(Builder $query, User $user): Builder
    {
        $assignmentIds = $user->assignments()->pluck('project_assignments.id')->filter()->values();
        $projectIds = $user->assignments()->pluck('project_assignments.project_id')->filter()->unique()->values();

        if ($assignmentIds->isEmpty() && $projectIds->isEmpty()) {
            return $query->whereRaw('1 = 0');
        }

        return $query
            ->where('visible_to_client', true)
            ->whereNotNull('file_path')
            ->where(function (Builder $builder) use ($assignmentIds, $projectIds) {
                if ($assignmentIds->isNotEmpty()) {
                    $builder->whereIn('project_assignment_id', $assignmentIds->all());
                }

                if ($projectIds->isNotEmpty()) {
                    $builder->orWhere(function (Builder $projectScope) use ($projectIds) {
                        $projectScope
                            ->whereNull('project_assignment_id')
                            ->whereIn('project_id', $projectIds->all());
                    });
                }
            });
    }

    public function scopeVisibleToPortalAssignment(Builder $query, ProjectAssignment $assignment): Builder
    {
        return $query
            ->where('project_id', $assignment->project_id)
            ->where('visible_to_client', true)
            ->whereNotNull('file_path')
            ->where(function (Builder $builder) use ($assignment) {
                $builder
                    ->whereNull('project_assignment_id')
                    ->orWhere('project_assignment_id', $assignment->id);
            });
    }

    public function scopeVisibleToPublicWebsite(Builder $query): Builder
    {
        return $query
            ->where('visible_to_client', true)
            ->whereNotNull('file_path')
            ->whereNull('project_assignment_id')
            ->whereNotNull('project_id')
            ->whereHas('project', fn (Builder $projectQuery) => $projectQuery->published());
    }

    public function scopeLibraryOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('is_featured')->orderBy('sort_order')->latest('id');
    }

    public static function gallerySectionOptions(): array
    {
        return [
            self::SECTION_EXTERIOR => 'Exterior',
            self::SECTION_INTERIOR => 'Interior',
            self::SECTION_GENERAL => 'Gallery',
            self::SECTION_LIVE_UPDATES => 'Live Updates',
        ];
    }

    public function getGallerySectionLabelAttribute(): string
    {
        return static::gallerySectionOptions()[$this->gallery_section]
            ?? Str::of($this->gallery_section ?: static::SECTION_GENERAL)->replace('-', ' ')->title()->toString();
    }

    public function getGallerySectionAttribute($value): string
    {
        return $value ?: static::SECTION_GENERAL;
    }

    public static function supportsGallerySections(): bool
    {
        return static::$gallerySectionsSupported ??= Schema::hasColumn('media_assets', 'gallery_section');
    }

    public function getFileUrlAttribute(): ?string
    {
        return AssetPath::url($this->file_path);
    }

    public function getThumbnailUrlAttribute(): string
    {
        return AssetPath::url($this->thumbnail_path ?: $this->file_path, 'images/project-1.jpg');
    }
}
