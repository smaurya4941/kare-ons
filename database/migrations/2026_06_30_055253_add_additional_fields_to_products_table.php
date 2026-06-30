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
            $table->string('brand')->nullable()->after('name');
            $table->string('pack_size')->nullable()->after('short_description');
            $table->text('ayurvedic_reference')->nullable()->after('benefits');
            $table->text('suitable_for')->nullable()->after('ayurvedic_reference');
            $table->text('disclaimer')->nullable()->after('suitable_for');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['brand', 'pack_size', 'ayurvedic_reference', 'suitable_for', 'disclaimer']);
        });
    }
};
