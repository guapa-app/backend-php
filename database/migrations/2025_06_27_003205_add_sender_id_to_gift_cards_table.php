<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Only add the column if it does not exist
        if (!Schema::hasColumn('gift_cards', 'sender_id')) {
            Schema::table('gift_cards', function (Blueprint $table) {
                $table->unsignedBigInteger('sender_id')->nullable()->after('code');
                // Add the foreign key if you want (optional, safe to skip if unsure)
                $table->foreign('sender_id')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        // Only drop the column if it exists
        if (Schema::hasColumn('gift_cards', 'sender_id')) {
            Schema::table('gift_cards', function (Blueprint $table) {
                $table->dropForeign(['sender_id']);
                $table->dropColumn('sender_id');
            });
        }
    }
};