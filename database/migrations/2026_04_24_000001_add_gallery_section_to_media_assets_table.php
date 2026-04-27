<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media_assets', function (Blueprint $table) {
            $table->string('gallery_section')->default('general')->after('media_type');
            $table->index(['project_id', 'gallery_section'], 'media_assets_project_gallery_section_index');
        });

        DB::table('projects')
            ->select(['id', 'title', 'gallery'])
            ->orderBy('id')
            ->chunkById(50, function ($projects): void {
                foreach ($projects as $project) {
                    $gallery = is_array($project->gallery)
                        ? $project->gallery
                        : (json_decode((string) ($project->gallery ?? '[]'), true) ?: []);

                    foreach (array_values($gallery) as $index => $filePath) {
                        if (! is_string($filePath) || trim($filePath) === '') {
                            continue;
                        }

                        $exists = DB::table('media_assets')
                            ->where('project_id', $project->id)
                            ->whereNull('project_assignment_id')
                            ->where('file_path', $filePath)
                            ->exists();

                        if ($exists) {
                            continue;
                        }

                        DB::table('media_assets')->insert([
                            'project_id' => $project->id,
                            'project_assignment_id' => null,
                            'uploaded_by' => null,
                            'title' => trim($project->title.' Gallery '.($index + 1)),
                            'media_type' => 'image',
                            'gallery_section' => 'general',
                            'file_path' => $filePath,
                            'thumbnail_path' => $filePath,
                            'notes' => 'Backfilled from the legacy project gallery.',
                            'is_featured' => $index === 0,
                            'visible_to_client' => true,
                            'sort_order' => $index + 1,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }
            });
    }

    public function down(): void
    {
        DB::table('media_assets')
            ->where('notes', 'Backfilled from the legacy project gallery.')
            ->delete();

        Schema::table('media_assets', function (Blueprint $table) {
            $table->dropIndex('media_assets_project_gallery_section_index');
            $table->dropColumn('gallery_section');
        });
    }
};
