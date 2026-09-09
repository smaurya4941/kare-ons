<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['products', 'categories'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                // Guarded so the migration is safe to run against an
                // environment where some of these columns already exist
                // (e.g. a DB whose migrations table drifted from its schema).
                if (! Schema::hasColumn($table->getTable(), 'seo_title')) {
                    $table->string('seo_title')->nullable()->after('name');
                }
                if (! Schema::hasColumn($table->getTable(), 'seo_description')) {
                    $table->text('seo_description')->nullable();
                }
                if (! Schema::hasColumn($table->getTable(), 'is_indexable')) {
                    $table->boolean('is_indexable')->default(true);
                }
            });
        }
    }

    public function down(): void
    {
        foreach (['products', 'categories'] as $table) {
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
