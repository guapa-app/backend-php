<?php

use Illuminate\Support\Facades\Log;
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
            // Add sender_id if missing
            if (!Schema::hasColumn('gift_cards', 'sender_id')) {
                $table->unsignedBigInteger('sender_id')->nullable()->after('code');
            }

            // Add recipient_id if missing
            if (!Schema::hasColumn('gift_cards', 'recipient_id')) {
                $table->unsignedBigInteger('recipient_id')->nullable()->after('sender_id');
            }

            // Add user_id if missing
            if (!Schema::hasColumn('gift_cards', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('recipient_id');
            }

            // Add vendor_id if missing
            if (!Schema::hasColumn('gift_cards', 'vendor_id')) {
                $table->unsignedBigInteger('vendor_id')->nullable()->after('user_id');
            }

            // Add product_id if missing
            if (!Schema::hasColumn('gift_cards', 'product_id')) {
                $table->unsignedBigInteger('product_id')->nullable()->after('vendor_id');
            }

            // Add offer_id if missing
            if (!Schema::hasColumn('gift_cards', 'offer_id')) {
                $table->unsignedBigInteger('offer_id')->nullable()->after('product_id');
            }

            // Add background_color if missing
            if (!Schema::hasColumn('gift_cards', 'background_color')) {
                $table->string('background_color')->nullable()->after('currency');
            }

            // Add background_image if missing
            if (!Schema::hasColumn('gift_cards', 'background_image')) {
                $table->string('background_image')->nullable()->after('background_color');
            }

            // Add background_image_id if missing
            if (!Schema::hasColumn('gift_cards', 'background_image_id')) {
                $table->unsignedBigInteger('background_image_id')->nullable()->after('background_image');
            }

            // Add redemption_method if missing
            if (!Schema::hasColumn('gift_cards', 'redemption_method')) {
                $table->string('redemption_method')->nullable()->after('status');
            }

            // Add redeemed_at if missing
            if (!Schema::hasColumn('gift_cards', 'redeemed_at')) {
                $table->timestamp('redeemed_at')->nullable()->after('expires_at');
            }

            // Add recipient_name if missing
            if (!Schema::hasColumn('gift_cards', 'recipient_name')) {
                $table->string('recipient_name')->nullable()->after('redeemed_at');
            }

            // Add recipient_email if missing
            if (!Schema::hasColumn('gift_cards', 'recipient_email')) {
                $table->string('recipient_email')->nullable()->after('recipient_name');
            }

            // Add recipient_number if missing
            if (!Schema::hasColumn('gift_cards', 'recipient_number')) {
                $table->string('recipient_number')->nullable()->after('recipient_email');
            }

            // Add notes if missing
            if (!Schema::hasColumn('gift_cards', 'notes')) {
                $table->text('notes')->nullable()->after('message');
            }

            // Add order_id if missing
            if (!Schema::hasColumn('gift_cards', 'order_id')) {
                $table->unsignedBigInteger('order_id')->nullable()->after('recipient_number');
            }

            // Add wallet_transaction_id if missing
            if (!Schema::hasColumn('gift_cards', 'wallet_transaction_id')) {
                $table->unsignedBigInteger('wallet_transaction_id')->nullable()->after('order_id');
            }

            // Add deleted_at for soft deletes if missing
            if (!Schema::hasColumn('gift_cards', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Add foreign key constraints if they don't exist
        // Note: We'll add them without checking if they exist since Laravel doesn't provide a direct way to check
        // If they already exist, the migration will fail gracefully and you can comment out the ones that cause issues
        try {
            Schema::table('gift_cards', function (Blueprint $table) {
                // Add foreign keys only if the columns exist
                if (Schema::hasColumn('gift_cards', 'sender_id')) {
                    $table->foreign('sender_id')->references('id')->on('users')->onDelete('set null');
                }
                if (Schema::hasColumn('gift_cards', 'recipient_id')) {
                    $table->foreign('recipient_id')->references('id')->on('users')->onDelete('set null');
                }
                if (Schema::hasColumn('gift_cards', 'user_id')) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                }
                if (Schema::hasColumn('gift_cards', 'vendor_id')) {
                    $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
                }
                if (Schema::hasColumn('gift_cards', 'product_id')) {
                    $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
                }
                if (Schema::hasColumn('gift_cards', 'offer_id')) {
                    $table->foreign('offer_id')->references('id')->on('offers')->onDelete('set null');
                }
                if (Schema::hasColumn('gift_cards', 'background_image_id')) {
                    $table->foreign('background_image_id')->references('id')->on('gift_card_backgrounds')->onDelete('set null');
                }
            });
        } catch (\Exception $e) {
            // If foreign key creation fails, we'll log it but continue
            // This is safe because the columns are still added
            Log::warning('Some foreign keys could not be created: ' . $e->getMessage());
        }

        // Add indexes if they don't exist
        try {
            Schema::table('gift_cards', function (Blueprint $table) {
                if (Schema::hasColumn('gift_cards', 'user_id') && Schema::hasColumn('gift_cards', 'status')) {
                    $table->index(['user_id', 'status']);
                }
            });
        } catch (\Exception $e) {
            Log::warning('Index could not be created: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gift_cards', function (Blueprint $table) {
            // Drop foreign keys first
            $foreignKeys = [
                'gift_cards_sender_id_foreign',
                'gift_cards_recipient_id_foreign',
                'gift_cards_user_id_foreign',
                'gift_cards_vendor_id_foreign',
                'gift_cards_product_id_foreign',
                'gift_cards_offer_id_foreign',
                'gift_cards_background_image_id_foreign'
            ];

            foreach ($foreignKeys as $foreignKey) {
                try {
                    $table->dropForeign($foreignKey);
                } catch (\Exception $e) {
                    // Ignore if foreign key doesn't exist
                }
            }

            // Drop indexes
            try {
                $table->dropIndex(['user_id', 'status']);
            } catch (\Exception $e) {
                // Ignore if index doesn't exist
            }

            // Drop columns if they exist
            $columns = [
                'sender_id', 'recipient_id', 'user_id', 'vendor_id', 'product_id', 'offer_id',
                'background_color', 'background_image', 'background_image_id', 'redemption_method',
                'redeemed_at', 'recipient_name', 'recipient_email', 'recipient_number',
                'notes', 'order_id', 'wallet_transaction_id', 'deleted_at'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('gift_cards', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
