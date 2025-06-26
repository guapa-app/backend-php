<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration supersedes all previous gift_cards, gift_card_settings, and gift_card_backgrounds migrations.
return new class extends Migration
{
    public function up(): void
    {
        // GIFT CARDS TABLE
        if (!Schema::hasTable('gift_cards')) {
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
                $table->string('currency', 10)->default('SAR');
                $table->enum('status', ['pending', 'active', 'used', 'expired', 'cancelled'])->default('pending');
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
            });
        } else {
            // Add missing columns/constraints if not present
            Schema::table('gift_cards', function (Blueprint $table) {
                $columns = Schema::getColumnListing('gift_cards');
                if (!in_array('vendor_id', $columns)) $table->unsignedBigInteger('vendor_id')->nullable()->after('user_id');
                if (!in_array('product_id', $columns)) $table->unsignedBigInteger('product_id')->nullable()->after('vendor_id');
                if (!in_array('offer_id', $columns)) $table->unsignedBigInteger('offer_id')->nullable()->after('product_id');
                if (!in_array('background_color', $columns)) $table->string('background_color')->nullable()->after('currency');
                if (!in_array('background_image', $columns)) $table->string('background_image')->nullable()->after('background_color');
                if (!in_array('background_image_id', $columns)) $table->unsignedBigInteger('background_image_id')->nullable()->after('background_image');
                if (!in_array('redeemed_at', $columns)) $table->timestamp('redeemed_at')->nullable()->after('expires_at');
                if (!in_array('recipient_name', $columns)) $table->string('recipient_name')->nullable()->after('redeemed_at');
                if (!in_array('recipient_email', $columns)) $table->string('recipient_email')->nullable()->after('recipient_name');
                if (!in_array('recipient_number', $columns)) $table->string('recipient_number')->nullable()->after('recipient_email');
                if (!in_array('notes', $columns)) $table->text('notes')->nullable()->after('message');
                if (!in_array('order_id', $columns)) $table->unsignedBigInteger('order_id')->nullable()->after('recipient_number');
                if (!in_array('wallet_transaction_id', $columns)) $table->unsignedBigInteger('wallet_transaction_id')->nullable()->after('order_id');
                if (!in_array('redemption_method', $columns)) $table->string('redemption_method')->nullable()->after('status');
                // Add foreign keys if not present
                // (Laravel doesn't provide a direct way to check for FKs, so this is best-effort)
            });
        }

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
    }

    public function down(): void
    {
        Schema::dropIfExists('gift_cards');
        Schema::dropIfExists('gift_card_settings');
        Schema::dropIfExists('gift_card_backgrounds');
    }
};
