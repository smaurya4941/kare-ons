<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN order_status ENUM('pending', 'confirmed', 'packed', 'shipped', 'delivered', 'returned', 'cancelled') DEFAULT 'pending'");
        
        Schema::table('orders', function (Blueprint $table) {
            $table->string('refund_status', 20)->default('none')->after('order_status'); // none, pending, partial, refunded
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('refund_status');
        });
        
        DB::statement("ALTER TABLE orders MODIFY COLUMN order_status ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending'");
    }
};
