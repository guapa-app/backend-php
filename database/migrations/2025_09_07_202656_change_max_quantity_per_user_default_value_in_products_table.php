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
            $table->integer('max_quantity_per_user')->default(100)->change();
        });

        DB::statement('UPDATE products SET max_quantity_per_user = 100');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('max_quantity_per_user')->default(10)->change();
        });

        DB::statement('UPDATE products SET max_quantity_per_user = 10');
    }
};
