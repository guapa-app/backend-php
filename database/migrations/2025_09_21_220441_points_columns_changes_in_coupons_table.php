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
        Schema::table('coupons', function (Blueprint $table) {
            $table->renameColumn('points', 'points_percentage');
            $table->string('points_percentage_source')->nullable()->after('single_user_usage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->renameColumn('points_percentage', 'points');
            $table->dropColumn('points_percentage_source');
        });
    }
};
