<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add new columns
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'invoiceable_type') && !Schema::hasColumn('invoices', 'invoiceable_id')) {

                $table->after('order_id', function ($table) {
                    $table->morphs('invoiceable');
                });
            }
        });

        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'order_id')) {
                // Migrate existing data
                DB::table('invoices')->whereNotNull('order_id')->update([
                    'invoiceable_type' => 'App\\Models\\Order',
                    'invoiceable_id' => DB::raw('order_id')
                ]);
                // Drop old column
                $table->dropForeign('invoices_order_id_foreign');
                $table->dropColumn('order_id');
            }
            if (Schema::hasColumn('invoices', 'marketing_campaign_id')) {
                // Migrate existing data
                DB::table('invoices')->whereNotNull('marketing_campaign_id')->update([
                    'invoiceable_type' => 'App\\Models\\MarketingCampaign',
                    'invoiceable_id' => DB::raw('marketing_campaign_id')
                ]);
                // Drop old column
                $table->dropForeign('invoices_marketing_campaign_id_foreign');
                $table->dropColumn('marketing_campaign_id');
            }
        });
    }
    public function down()
    {
        // Add old columns
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->after('id');
                $table->unsignedBigInteger('marketing_campaign_id')->nullable()->after('order_id');

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('marketing_campaign_id')
                ->references('id')
                ->on('marketing_campaigns')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });

        // Migrate data back (this will lose any new types of invoiceables added)
        DB::table('invoices')
            ->where('invoiceable_type', 'App\\Models\\Order')
            ->update(['order_id' => DB::raw('invoiceable_id')]);

        DB::table('invoices')
            ->where('invoiceable_type', 'App\\Models\\MarketingCampaign')
            ->update(['marketing_campaign_id' => DB::raw('invoiceable_id')]);

        // Drop new columns
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('invoiceable_type');
            $table->dropColumn('invoiceable_id');
        });

    }
};
