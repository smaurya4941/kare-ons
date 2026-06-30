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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Admin who made the change
            $table->string('type'); // 'purchase', 'adjustment', 'order_fulfillment', 'order_cancellation', 'return'
            $table->integer('quantity'); // Positive for IN, Negative for OUT
            $table->text('notes')->nullable();
            $table->string('reference_id')->nullable(); // Order ID or PO ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
