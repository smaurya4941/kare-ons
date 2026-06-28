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
            $table->string('home_hero_title')->nullable();
            $table->text('home_hero_subtitle')->nullable();
            $table->string('home_hero_bg')->nullable();
            $table->string('home_cta_text')->nullable();
            $table->string('home_cta_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'home_hero_title',
                'home_hero_subtitle',
                'home_hero_bg',
                'home_cta_text',
                'home_cta_link'
            ]);
        });
    }
};
