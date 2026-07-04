<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Audit trail of admin actions — who did what, to which record, and what
     * changed. Used by the admin Activity Log screen.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            // The admin who performed the action (nullable = system/guest action).
            $table->foreignId('causer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('causer_name')->nullable();     // snapshot, survives user deletion
            $table->string('event', 30)->index();          // created|updated|deleted|...
            $table->string('log_name', 50)->default('default')->index();
            $table->text('description');
            // The record the action was performed on (polymorphic, nullable).
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->json('properties')->nullable();        // { old: {...}, attributes: {...} }
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['subject_type', 'subject_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
