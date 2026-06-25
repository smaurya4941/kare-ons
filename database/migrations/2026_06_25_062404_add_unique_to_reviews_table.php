<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add a unique constraint so each user can only write one review per product.
     * Also adds a `comment` column alias if needed (we store data in `review` column).
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Unique constraint: one review per user per product
            $table->unique(['user_id', 'product_id'], 'reviews_user_product_unique');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique('reviews_user_product_unique');
        });
    }
};
