<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Store-wide admin notifications (new orders, low stock, pending reviews,
     * customer messages, etc). These are shared across all admin users — read
     * state is tracked centrally via `read_at`.
     */
    public function up(): void
    {
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50)->index();          // order|low_stock|review|message
            $table->string('title');
            $table->text('message')->nullable();
            $table->string('url')->nullable();             // deep link into the admin panel
            $table->string('icon', 50)->nullable();        // material symbol name
            $table->string('color', 20)->nullable();       // tailwind accent (indigo, red, ...)
            // Optional link to the source record so we can de-duplicate & clean up.
            $table->string('notifiable_type')->nullable();
            $table->unsignedBigInteger('notifiable_id')->nullable();
            $table->json('data')->nullable();
            $table->timestamp('read_at')->nullable()->index();
            $table->timestamps();

            $table->index(['notifiable_type', 'notifiable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};
