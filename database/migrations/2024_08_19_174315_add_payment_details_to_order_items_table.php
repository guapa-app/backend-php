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
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('amount_to_pay')
                ->nullable()
                ->after('amount');
            $table->string('taxes')
                ->nullable()
                ->after('amount_to_pay');
            $table->string('title')
                ->nullable()
                ->after('taxes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('taxes');
            $table->dropColumn('amount_to_pay');
        });
    }
};
