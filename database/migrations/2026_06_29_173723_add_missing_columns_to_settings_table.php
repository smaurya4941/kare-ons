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
            $table->string('timezone')->nullable()->default('Asia/Kolkata');
            $table->string('currency')->nullable()->default('INR');
            $table->string('language')->nullable()->default('en');
            
            $table->string('seo_meta_title')->nullable();
            $table->text('seo_meta_description')->nullable();
            $table->text('seo_meta_keywords')->nullable();

            $table->string('smtp_host')->nullable();
            $table->string('smtp_port')->nullable();
            $table->string('smtp_user')->nullable();
            $table->string('smtp_password')->nullable();
            $table->string('smtp_encryption')->nullable();
            $table->string('smtp_from_address')->nullable();

            $table->string('whatsapp_api_key')->nullable();
            $table->string('whatsapp_number')->nullable();

            $table->text('invoice_company_details')->nullable();
            $table->string('invoice_gst_number')->nullable();
            $table->string('invoice_prefix')->nullable()->default('KO-');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'timezone', 'currency', 'language',
                'seo_meta_title', 'seo_meta_description', 'seo_meta_keywords',
                'smtp_host', 'smtp_port', 'smtp_user', 'smtp_password', 'smtp_encryption', 'smtp_from_address',
                'whatsapp_api_key', 'whatsapp_number',
                'invoice_company_details', 'invoice_gst_number', 'invoice_prefix'
            ]);
        });
    }
};
