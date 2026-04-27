<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultation_blocked_dates', function (Blueprint $table) {
            $table->id();
            $table->date('blocked_on');
            $table->boolean('is_all_day')->default(true);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->index('blocked_on');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_blocked_dates');
    }
};
