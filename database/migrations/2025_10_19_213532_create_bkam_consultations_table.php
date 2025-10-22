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
        Schema::create('bkam_consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('taxonomy_id')->constrained()->onDelete('cascade');

            $table->string('status')->default('pending');            
            $table->decimal('consultation_fee', 10, 2)->default(0);
            $table->decimal('taxes', 10, 2)->default(0);
            $table->string('payment_status')->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();

            $table->text('details')->nullable();
            $table->json('medical_history')->nullable();
            $table->string('invoice_url')->nullable();

            // Timestamps for different stages of consultation
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bkam_consultations');
    }
};
