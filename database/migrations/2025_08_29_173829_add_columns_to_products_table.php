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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('stock')->default(0)->after('type');
            $table->boolean('is_shippable')->default(false)->after('stock');
            $table->integer('min_quantity_per_user')->default(1)->after('is_shippable');
            $table->integer('max_quantity_per_user')->default(10)->after('min_quantity_per_user');
            $table->integer('days_of_delivery')->default(1)->after('max_quantity_per_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
