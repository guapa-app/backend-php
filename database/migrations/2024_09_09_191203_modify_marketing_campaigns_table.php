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
            // Modify existing columns
            // TODO it could be char 20, 25 not string +
            // no need for you are in development and not merged yet
            //so you can update it on original file.

            $table->string('channel', 20)->change();
            $table->string('audience_type', 20)->change();
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
