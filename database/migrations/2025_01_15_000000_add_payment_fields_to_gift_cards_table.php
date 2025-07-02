<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('gift_cards', function (Blueprint $table) {
            // Add payment-related fields
            $table->string('payment_status')->default('pending')->after('status');
            $table->string('payment_method')->nullable()->after('payment_status');
            $table->string('payment_reference')->nullable()->after('payment_method');
            $table->string('payment_gateway')->nullable()->after('payment_reference');
            $table->decimal('tax_amount', 10, 2)->default(0)->after('amount');
            $table->decimal('total_amount', 10, 2)->default(0)->after('tax_amount');
            $table->string('invoice_url')->nullable()->after('payment_gateway');

            // Add index for payment status queries
            $table->index(['payment_status', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gift_cards', function (Blueprint $table) {
            $table->dropIndex(['payment_status', 'status']);
            $table->dropColumn([
                'payment_status',
                'payment_method',
                'payment_reference',
                'payment_gateway',
                'tax_amount',
                'total_amount',
                'invoice_url'
            ]);
        });
    }
};