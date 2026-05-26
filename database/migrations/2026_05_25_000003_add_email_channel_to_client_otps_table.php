<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_otps', function (Blueprint $table) {
            if (! Schema::hasColumn('client_otps', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }

            if (! Schema::hasColumn('client_otps', 'delivery_channel')) {
                $table->string('delivery_channel')->default('email')->after('delivery_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('client_otps', function (Blueprint $table) {
            if (Schema::hasColumn('client_otps', 'delivery_channel')) {
                $table->dropColumn('delivery_channel');
            }

            if (Schema::hasColumn('client_otps', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};
