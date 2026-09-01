<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Brings `blogs` and `pages` up to the same seo_title / seo_description /
     * is_indexable convention already added to `products` and `categories`
     * (see 2026_08_29_140000_add_seo_fields_to_products_and_categories_tables.php),
     * then backfills the new columns from the legacy meta_title / meta_description
     * columns so existing content isn't blanked out.
     */
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('seo_title')->nullable()->after('title');
            $table->text('seo_description')->nullable()->after('seo_title');
            $table->boolean('is_indexable')->default(true)->after('seo_description');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->string('seo_title')->nullable()->after('title');
            $table->text('seo_description')->nullable()->after('seo_title');
            $table->boolean('is_indexable')->default(true)->after('seo_description');
        });

        // Backfill: copy legacy meta_* values into the new seo_* columns wherever
        // the new column is empty and the old one has data. Uses the query builder
        // (not Eloquent) so no model events fire during the migration.
        foreach (['products', 'categories', 'blogs', 'pages'] as $table) {
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn(['seo_title', 'seo_description', 'is_indexable']);
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['seo_title', 'seo_description', 'is_indexable']);
        });
    }
};
