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
        Schema::table('work_days', function (Blueprint $table) {
            $table->string('type')->default('offline')->after('vendor_id');
            $table->boolean('is_active')->default(false)->after('day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_days', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('is_active');
        });
    }
};
