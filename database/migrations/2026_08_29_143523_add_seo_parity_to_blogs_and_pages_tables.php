<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Brings `blogs` and `pages` up to the same seo_title / seo_description /
     * is_indexable convention already added to `products` and `categories`
     * (see 2026_08_29_140000_add_seo_fields_to_products_and_categories_tables.php),
     * then backfills the new columns from the legacy meta_title / meta_description
     * columns so existing content isn't blanked out.
     *
     * Every column add is guarded so this is safe to run on an environment
     * whose migrations table drifted from its actual schema.
     */
    public function up(): void
    {
        foreach (['blogs', 'pages'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                if (! Schema::hasColumn($table->getTable(), 'seo_title')) {
                    $table->string('seo_title')->nullable()->after('title');
                }
                if (! Schema::hasColumn($table->getTable(), 'seo_description')) {
                    $table->text('seo_description')->nullable();
                }
                if (! Schema::hasColumn($table->getTable(), 'is_indexable')) {
                    $table->boolean('is_indexable')->default(true);
                }
            });
        }

        // Backfill: copy legacy meta_* values into the new seo_* columns wherever
        // the new column is empty and the old one has data. Uses the query builder
        // (not Eloquent) so no model events fire during the migration.
        foreach (['products', 'categories', 'blogs', 'pages'] as $table) {
            if (! Schema::hasColumn($table, 'seo_title') || ! Schema::hasColumn($table, 'meta_title')) {
                continue;
            }

            DB::table($table)
                ->whereNull('seo_title')
                ->whereNotNull('meta_title')
                ->update(['seo_title' => DB::raw('meta_title')]);

            DB::table($table)
                ->whereNull('seo_description')
                ->whereNotNull('meta_description')
                ->update(['seo_description' => DB::raw('meta_description')]);
        }
    }

    public function down(): void
    {
        foreach (['blogs', 'pages'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                foreach (['seo_title', 'seo_description', 'is_indexable'] as $column) {
                    if (Schema::hasColumn($table->getTable(), $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
