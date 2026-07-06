<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedSmallInteger('total_units')->nullable()->after('price_from');
            $table->unsignedSmallInteger('available_units')->nullable()->after('total_units');
            $table->string('built_area')->nullable()->after('available_units');
            $table->text('residence_profile')->nullable()->after('built_area');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['total_units', 'available_units', 'built_area', 'residence_profile']);
        });
    }
};
