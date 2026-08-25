<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('home_ingredient_spotlight_title')->nullable()->after('home_cta_link');
            $table->text('home_ingredient_spotlight_ingredients')->nullable()->after('home_ingredient_spotlight_title');
            $table->string('home_ingredient_spotlight_bg')->nullable()->after('home_ingredient_spotlight_ingredients');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'home_ingredient_spotlight_title',
                'home_ingredient_spotlight_ingredients',
                'home_ingredient_spotlight_bg'
            ]);
        });
    }
};
