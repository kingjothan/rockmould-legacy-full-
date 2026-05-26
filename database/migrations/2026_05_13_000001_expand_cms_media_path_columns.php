<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Allow CDN URLs and signed media links that are longer than a normal local path.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach ($this->columns() as $table => $columns) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($columns as $column) {
                if (! Schema::hasColumn($table, $column)) {
                    continue;
                }

                DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` TEXT NULL");
            }
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach ($this->columns() as $table => $columns) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($columns as $column) {
                if (! Schema::hasColumn($table, $column)) {
                    continue;
                }

                DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` VARCHAR(255) NULL");
            }
        }
    }

    private function columns(): array
    {
        return [
            'documents' => ['file_path'],
            'kyc_requests' => ['identification_path'],
            'media_assets' => ['file_path', 'thumbnail_path'],
            'posts' => ['cover_image'],
            'projects' => ['cover_image'],
            'services' => ['image_path'],
            'team_members' => ['image_path'],
            'testimonials' => ['image_path'],
            'users' => ['avatar_path'],
        ];
    }
};
