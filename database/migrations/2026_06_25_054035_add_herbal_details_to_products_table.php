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
        Schema::table('products', function (Blueprint $table) {
            $table->text('benefits')->nullable()->after('description');
            $table->text('ingredients')->nullable()->after('benefits');
            $table->text('usage_instructions')->nullable()->after('ingredients');
            $table->text('storage_instructions')->nullable()->after('usage_instructions');
            $table->text('precautions')->nullable()->after('storage_instructions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'benefits', 
                'ingredients', 
                'usage_instructions', 
                'storage_instructions', 
                'precautions'
            ]);
        });
    }
};
