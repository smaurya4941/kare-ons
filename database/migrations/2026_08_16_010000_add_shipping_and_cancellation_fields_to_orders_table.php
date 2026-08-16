<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('courier_name', 100)->nullable()->after('refund_status');
            $table->string('tracking_number', 100)->nullable()->after('courier_name');
            $table->string('tracking_url', 500)->nullable()->after('tracking_number');
            $table->date('expected_delivery_date')->nullable()->after('tracking_url');
            $table->string('cancellation_reason', 500)->nullable()->after('expected_delivery_date');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'courier_name',
                'tracking_number',
                'tracking_url',
                'expected_delivery_date',
                'cancellation_reason',
            ]);
        });
    }
};
