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
        Schema::create('marketing_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->enum('channel', ['whatsapp','email', 'sms'])->default('whatsapp');
            $table->enum('audience_type', ['vendor_customers','guapa_customers']);
            $table->integer('audience_count');
            $table->decimal('message_cost', 10, 2);
            $table->decimal('taxes', 10, 2);
            $table->decimal('total_cost', 10, 2);
            $table->string('invoice_url')->nullable();
            $table->enum('status', ['pending','completed','expired','failed'])->default('pending');
            $table->morphs('campaignable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_campaigns');
    }
};
