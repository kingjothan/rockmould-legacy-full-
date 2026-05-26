<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_otps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('phone');
            $table->string('code_hash');
            $table->timestamp('expires_at');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('consumed_at')->nullable();
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->string('request_ip', 64)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('delivery_status')->default('pending');
            $table->string('gateway')->nullable();
            $table->string('gateway_message_id')->nullable();
            $table->text('gateway_response')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'consumed_at', 'expires_at']);
            $table->index(['phone', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_otps');
    }
};
