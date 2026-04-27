<?php

namespace App\Models;

use App\Support\AssetPath;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'visible_to_client' => 'boolean',
            'is_archived' => 'boolean',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
            ->where('is_archived', false)
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
            ->where('is_archived', false)
            ->where(function (Builder $builder) use ($assignment) {
                $builder
                    ->whereNull('project_assignment_id')
                    ->orWhere('project_assignment_id', $assignment->id);
            });
    }

    public function scopeLibraryOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('visible_to_client')->orderByDesc('created_at');
    }

    public function getFileUrlAttribute(): ?string
    {
        return AssetPath::url($this->file_path);
    }
}
