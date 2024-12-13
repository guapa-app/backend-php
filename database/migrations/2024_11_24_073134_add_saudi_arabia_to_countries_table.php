<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            DB::table('countries')->insert([
                'name' => 'المملكة العربية السعودية',
                'currency_code' => 'SAR',
                'phone_code' => '+966',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            DB::table('countries')->where('name', 'المملكة العربية السعودية')->delete();
        });
    }
};
