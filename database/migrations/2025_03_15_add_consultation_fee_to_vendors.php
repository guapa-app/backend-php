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
        Schema::table('vendors', function (Blueprint $table) {
            $table->boolean('accept_online_consultation')->default(0)->after('accept_appointment');
            $table->decimal('consultation_fee', 10, 2)->default(0.00)->after('accept_appointment');
            $table->integer('session_duration')->default(30)->after('consultation_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('accept_online_consultation');
            $table->dropColumn('consultation_fee');
            $table->dropColumn('session_duration');
        });
    }
};
