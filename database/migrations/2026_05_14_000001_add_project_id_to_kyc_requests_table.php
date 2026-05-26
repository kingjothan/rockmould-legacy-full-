<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('kyc_requests', 'project_id')) {
            Schema::table('kyc_requests', function (Blueprint $table) {
                $table->foreignId('project_id')
                    ->nullable()
                    ->after('project_of_interest')
                    ->constrained('projects')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('kyc_requests', 'project_id')) {
            Schema::table('kyc_requests', function (Blueprint $table) {
                $table->dropConstrainedForeignId('project_id');
            });
        }
    }
};
