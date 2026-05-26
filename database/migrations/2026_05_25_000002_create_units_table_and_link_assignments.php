<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('label');
            $table->string('status')->default('assigned');
            $table->string('unit_type')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['project_id', 'label']);
            $table->index(['client_id', 'project_id']);
        });

        Schema::table('project_assignments', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->after('user_id')->constrained('units')->nullOnDelete();
        });

        DB::table('project_assignments')
            ->orderBy('id')
            ->get()
            ->each(function (object $assignment): void {
                $unit = DB::table('units')
                    ->where('project_id', $assignment->project_id)
                    ->where('label', $assignment->unit_label)
                    ->first();

                $unitId = $unit?->id ?? DB::table('units')->insertGetId([
                    'project_id' => $assignment->project_id,
                    'client_id' => $assignment->user_id,
                    'label' => $assignment->unit_label,
                    'status' => $assignment->status,
                    'price' => $assignment->contract_amount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('project_assignments')
                    ->where('id', $assignment->id)
                    ->update(['unit_id' => $unitId]);
            });
    }

    public function down(): void
    {
        Schema::table('project_assignments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('unit_id');
        });

        Schema::dropIfExists('units');
    }
};
