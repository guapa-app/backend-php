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
        Schema::table('wallets', function (Blueprint $table) {
            $table->foreignId('vendor_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallet', function (Blueprint $table) {
            $table->dropConstrainedForeignId('vendor_id');
            $table->foreignId('user_id')->change();
        });
    }
};
