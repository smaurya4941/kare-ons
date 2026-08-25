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
            $table->string('home_expert_name')->nullable();
            $table->string('home_expert_designation')->nullable();
            $table->string('home_expert_description')->nullable();
            $table->text('home_expert_quote')->nullable();
            $table->string('home_expert_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'home_expert_name',
                'home_expert_designation',
                'home_expert_description',
                'home_expert_quote',
                'home_expert_image',
            ]);
        });
    }
};
