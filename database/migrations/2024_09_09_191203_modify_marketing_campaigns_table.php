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
        Schema::table('marketing_campaigns', function (Blueprint $table) {
            $table->char('channel', 20)->change();
            $table->char('audience_type', 20)->change();
            $table->char('status', 12)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_campaigns', function (Blueprint $table) {
            // Revert changes if needed
            $table->enum('channel', ['whatsapp', 'email', 'sms'])->change();
            $table->enum('audience_type', ['vendor_customers', 'guapa_customers'])->change();
            $table->enum('status', ['pending', 'completed', 'expired', 'failed'])->change();
        });
    }
};
