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
        // Add `country_id` to `users` table
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->default(1)->index()->after('status');
        });

        // Add `country_id` to `vendors` table
        Schema::table('vendors', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->default(1)->index()->after('id');
        });

        // Add `country_id` to `products` table
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->default(1)->index()->after('id');
        });

        // Add `country_id` to `posts` table
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->default(1)->index()->after('id');
        });

        // Add `country_id` to `orders` table
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->default(1)->index()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove `country_id` from `users` table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('country_id');
        });

        // Remove `country_id` from `vendors` table
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('country_id');
        });

        // Remove `country_id` from `products` table
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('country_id');
        });

        // Remove `country_id` from `posts` table
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('country_id');
        });

        // Remove `country_id` from `orders` table
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('country_id');
        });
    }
};
