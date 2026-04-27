<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->string('unit_label');
            $table->string('status')->default('active');
            $table->unsignedTinyInteger('progress_percentage')->default(0);
            $table->decimal('contract_amount', 15, 2)->default(0);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('outstanding_balance', 15, 2)->default(0);
            $table->decimal('next_payment_amount', 15, 2)->nullable();
            $table->date('next_payment_due_at')->nullable();
            $table->date('start_date')->nullable();
            $table->date('handover_date')->nullable();
            $table->string('account_manager_name')->nullable();
            $table->string('account_manager_email')->nullable();
            $table->string('account_manager_phone')->nullable();
            $table->text('client_notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_assignments');
    }
};
