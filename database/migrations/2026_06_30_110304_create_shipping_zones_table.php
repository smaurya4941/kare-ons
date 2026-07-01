<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('coverage')->nullable(); // comma separated list of pincodes/states
            $table->decimal('base_charge', 10, 2)->default(0);
            $table->decimal('free_shipping_threshold', 10, 2)->nullable();
            $table->decimal('cod_charge', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_zones');
    }
};
