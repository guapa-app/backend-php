<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('gift_cards', function (Blueprint $table) {
            // Add missing fields if they don't exist
            if (!Schema::hasColumn('gift_cards', 'gift_type')) {
                $table->enum('gift_type', ['wallet', 'order'])->default('wallet')->after('type');
            }

            if (!Schema::hasColumn('gift_cards', 'order_id')) {
                $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null')->after('offer_id');
            }

            if (!Schema::hasColumn('gift_cards', 'wallet_transaction_id')) {
                $table->foreignId('wallet_transaction_id')->nullable()->constrained('transactions')->onDelete('set null')->after('order_id');
            }

            if (!Schema::hasColumn('gift_cards', 'background_image_id')) {
                $table->foreignId('background_image_id')->nullable()->constrained('gift_card_backgrounds')->onDelete('set null')->after('background_image');
            }

            if (!Schema::hasColumn('gift_cards', 'redemption_method')) {
                $table->enum('redemption_method', ['pending', 'wallet', 'order'])->default('pending')->after('status');
            }

            if (!Schema::hasColumn('gift_cards', 'notes')) {
                $table->text('notes')->nullable()->after('message');
            }

            // Add indexes for better performance if they don't exist
            if (!Schema::hasIndex('gift_cards', 'gift_cards_gift_type_status_index')) {
                $table->index(['gift_type', 'status']);
            }

            if (!Schema::hasIndex('gift_cards', 'gift_cards_user_id_status_index')) {
                $table->index(['user_id', 'status']);
            }

            if (!Schema::hasIndex('gift_cards', 'gift_cards_created_by_status_index')) {
                $table->index(['created_by', 'status']);
            }

            if (!Schema::hasIndex('gift_cards', 'gift_cards_code_index')) {
                $table->index(['code']);
            }

            if (!Schema::hasIndex('gift_cards', 'gift_cards_expires_at_index')) {
                $table->index(['expires_at']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gift_cards', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['gift_type', 'status']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['created_by', 'status']);
            $table->dropIndex(['code']);
            $table->dropIndex(['expires_at']);

            // Drop foreign keys
            $table->dropForeign(['order_id']);
            $table->dropForeign(['wallet_transaction_id']);
            $table->dropForeign(['background_image_id']);

            // Drop columns
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