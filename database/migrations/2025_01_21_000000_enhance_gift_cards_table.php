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
        Schema::table('gift_cards', function (Blueprint $table) {
            // Add gift card type (wallet or order)
            $table->enum('gift_type', ['wallet', 'order'])->default('wallet')->after('type');

            // Add order relationship for order-type gift cards
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null')->after('offer_id');

            // Add wallet transaction relationship
            $table->foreignId('wallet_transaction_id')->nullable()->constrained('transactions')->onDelete('set null')->after('order_id');

            // Add background image ID for admin-uploaded backgrounds
            $table->foreignId('background_image_id')->nullable()->constrained('gift_card_backgrounds')->onDelete('set null')->after('background_image');

            // Add redemption method tracking
            $table->enum('redemption_method', ['wallet', 'order', 'pending'])->default('pending')->after('status');

            // Add notes field for admin/internal use
            $table->text('notes')->nullable()->after('message');

            // Add index for better performance
            $table->index(['gift_type', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['created_by', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gift_cards', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['wallet_transaction_id']);
            $table->dropForeign(['background_image_id']);
            $table->dropIndex(['gift_type', 'status']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['created_by', 'status']);

            $table->dropColumn([
                'gift_type',
                'order_id',
                'wallet_transaction_id',
                'background_image_id',
                'redemption_method',
                'notes'
            ]);
        });
    }
};
