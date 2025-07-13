<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration supersedes all previous gift_cards, gift_card_settings, and gift_card_backgrounds migrations.
return new class extends Migration
{
    public function up(): void
    {

        // GIFT CARD BACKGROUNDS TABLE
        if (!Schema::hasTable('gift_card_backgrounds')) {
            Schema::create('gift_card_backgrounds', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('uploaded_by');
                $table->timestamps();
            });
        }

        if (Schema::hasColumn('orders', 'gift_card_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['gift_card_id']);
                $table->dropColumn('gift_card_id');
            });
        }

        Schema::dropIfExists('gift_cards');
        // GIFT CARDS TABLE
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->unsignedBigInteger('recipient_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('offer_id')->nullable();
            $table->decimal('amount', 12, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('currency', 10)->default('SAR');
            $table->enum('status', ['pending', 'active', 'used', 'expired', 'cancelled'])->default('pending');
            // Add payment-related fields
            $table->string('payment_status')->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('payment_gateway')->nullable();
            $table->string('invoice_url')->nullable();

            $table->enum('gift_type', ['wallet', 'order'])->default('wallet');
            $table->text('message')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('redeemed_at')->nullable();
            $table->string('background_color')->nullable();
            $table->string('background_image')->nullable();
            $table->unsignedBigInteger('background_image_id')->nullable();
            $table->string('redemption_method')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('recipient_email')->nullable();
            $table->string('recipient_number')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('wallet_transaction_id')->nullable();


            $table->softDeletes();
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('set null');
            $table->foreign('background_image_id')->references('id')->on('gift_card_backgrounds')->onDelete('set null');
            $table->index(['user_id', 'status']);

            // Add index for payment status queries
            $table->index(['payment_status', 'status']);
        });


        // GIFT CARD SETTINGS TABLE
        if (!Schema::hasTable('gift_card_settings')) {
            Schema::create('gift_card_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->json('value');
                $table->enum('type', ['string', 'array', 'boolean', 'integer'])->default('string');
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('gift_cards');
        Schema::dropIfExists('gift_card_settings');
        Schema::dropIfExists('gift_card_backgrounds');
    }
};
