<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('location');
            $table->string('category');
            $table->string('status')->default('upcoming');
            $table->text('overview');
            $table->longText('description')->nullable();
            $table->json('features')->nullable();
            $table->json('highlights')->nullable();
            $table->json('gallery')->nullable();
            $table->json('floor_plans')->nullable();
            $table->json('documents')->nullable();
            $table->string('cover_image')->nullable();
            $table->unsignedTinyInteger('progress')->default(0);
            $table->decimal('budget', 15, 2)->nullable();
            $table->decimal('price_from', 15, 2)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_portfolio')->default(false);
            $table->boolean('is_published')->default(true);
            $table->date('launch_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->string('map_embed')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
