<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('status');
            $table->boolean('is_best_seller')->default(false)->after('is_featured');
            $table->boolean('is_trending')->default(false)->after('is_best_seller');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'is_best_seller', 'is_trending']);
        });
    }
};
