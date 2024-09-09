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
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('marketing_campaign_id')->nullable()->after('order_id');
            $table->unsignedBigInteger('order_id')->nullable()->change();

            $table->foreign('marketing_campaign_id')
                ->references('id')
                ->on('marketing_campaigns')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropColumn('campaign_id');
                $table->unsignedBigInteger('order_id')->nullable(false)->change();
            });
        });
    }
};
