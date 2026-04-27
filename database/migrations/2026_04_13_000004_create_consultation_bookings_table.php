<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultation_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('consultation_type');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone', 50);
            $table->string('company_name')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->date('consultation_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('timezone')->default(config('consultations.booking.timezone', 'Africa/Lagos'));
            $table->string('status')->default('pending');
            $table->string('meeting_link')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('assigned_staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('client_ip', 45)->nullable();
            $table->timestamps();

            $table->index(['consultation_date', 'status']);
            $table->index(['consultation_date', 'start_time']);
            $table->index(['email', 'phone']);
            $table->index('consultation_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_bookings');
    }
};
