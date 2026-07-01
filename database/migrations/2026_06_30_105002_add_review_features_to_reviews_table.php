<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->json('images')->nullable()->after('review');
            $table->boolean('is_verified_purchase')->default(false)->after('status');
            $table->text('admin_reply')->nullable()->after('is_verified_purchase');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['images', 'is_verified_purchase', 'admin_reply']);
        });
    }
};
