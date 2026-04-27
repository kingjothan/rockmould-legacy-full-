<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultation_settings', function (Blueprint $table) {
            $table->id();
            $table->string('timezone')->default(config('consultations.booking.timezone', 'Africa/Lagos'));
            $table->unsignedSmallInteger('slot_duration_minutes')->default((int) config('consultations.booking.slot_duration_minutes', 60));
            $table->unsignedSmallInteger('buffer_minutes')->default((int) config('consultations.booking.buffer_minutes', 15));
            $table->unsignedSmallInteger('booking_window_days')->default((int) config('consultations.booking.booking_window_days', 60));
            $table->unsignedSmallInteger('minimum_notice_hours')->default((int) config('consultations.booking.minimum_notice_hours', 12));
            $table->string('notification_email')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_settings');
    }
};
