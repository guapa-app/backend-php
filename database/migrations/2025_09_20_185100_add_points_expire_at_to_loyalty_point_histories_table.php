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
        Schema::table('loyalty_point_histories', function (Blueprint $table) {
            $table->dateTime('points_expire_at')->nullable()->after('points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loyalty_point_histories', function (Blueprint $table) {
            $table->dropColumn('points_expire_at');
        });
    }
};
