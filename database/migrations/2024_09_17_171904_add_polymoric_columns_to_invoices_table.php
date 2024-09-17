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
            $table->after('order_id', function ($table) {
                $table->morphs('invoiceable');
            });

        });

        // Migrate existing data
        DB::table('invoices')->whereNotNull('order_id')->update([
            'invoiceable_type' => 'App\\Models\\Order',
            'invoiceable_id' => DB::raw('order_id')
        ]);

        DB::table('invoices')->whereNotNull('marketing_campaign_id')->update([
            'invoiceable_type' => 'App\\Models\\MarketingCampaign',
            'invoiceable_id' => DB::raw('marketing_campaign_id')
        ]);

        // Drop old columns
//        Schema::table('invoices', function (Blueprint $table) {
//            $table->dropForeign(['order_id']);
//            $table->dropColumn('order_id');
//            $table->dropColumn('marketing_campaign_id');
//        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('marketing_campaign_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->dropIndex(['invoiceable_type', 'invoiceable_id']);
            $table->dropColumn('invoiceable_type');
            $table->dropColumn('invoiceable_id');
        });

        // Migrate data back (this will lose any new types of invoiceables added)
        DB::table('invoices')
            ->where('invoiceable_type', 'App\\Models\\Order')
            ->update(['order_id' => DB::raw('invoiceable_id')]);

        DB::table('invoices')
            ->where('invoiceable_type', 'App\\Models\\MarketingCampaign')
            ->update(['marketing_campaign_id' => DB::raw('invoiceable_id')]);
    }
};
