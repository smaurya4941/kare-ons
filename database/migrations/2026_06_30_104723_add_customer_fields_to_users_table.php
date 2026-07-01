<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('status');
            $table->integer('reward_points')->default(0)->after('notes');
            $table->decimal('wallet_balance', 10, 2)->default(0.00)->after('reward_points');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['notes', 'reward_points', 'wallet_balance']);
        });
    }
};
