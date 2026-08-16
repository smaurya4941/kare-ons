<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Indexes for columns that are actually filtered/sorted on in hot query
 * paths (confirmed by auditing controllers, not added speculatively):
 *
 *  - products: status (nearly every storefront/API query), is_featured /
 *    is_best_seller / is_trending (homepage rails), created_at (latest /
 *    new-arrivals sort). category_id, brand_id, tax_id, slug, sku already
 *    have indexes from their FK/unique constraints.
 *  - orders: order_status + payment_status (admin filters, dashboard KPIs —
 *    the dashboard alone runs 10+ separate order_status count queries),
 *    created_at (30-day sales trend, today's-sales, report date ranges).
 *    user_id/address_id already indexed via FK.
 *  - order_items: order_id/product_id already indexed via FK — no gap here.
 *  - categories/brands: status (filtered on almost every read).
 *  - reviews: composite (product_id, status) — the moderated-reviews
 *    relation used by every product card's withAvg/withCount query.
 *  - users: role (customer list/count queries in admin).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index('status');
            $table->index('is_featured');
            $table->index('is_best_seller');
            $table->index('is_trending');
            $table->index('created_at');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('order_status');
            $table->index('payment_status');
            $table->index('created_at');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['product_id', 'status']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['is_best_seller']);
            $table->dropIndex(['is_trending']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['order_status']);
            $table->dropIndex(['payment_status']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'status']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
        });
    }
};
