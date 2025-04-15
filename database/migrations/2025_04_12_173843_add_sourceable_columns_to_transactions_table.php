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
        Schema::table('transactions', function (Blueprint $table) {
            // Add polymorphic columns
            $table->nullableMorphs('sourceable');
        });

        // Migrate existing data
        DB::table('transactions')->whereNotNull('order_id')->update([
            'sourceable_type' => 'App\\Models\\Order',
            'sourceable_id' => DB::raw('order_id')
        ]);

        // Drop the old order_id column
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Add back the order_id column
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
        });

        // Migrate data back
        DB::table('transactions')->where('sourceable_type', 'App\\Models\\Order')->update([
            'order_id' => DB::raw('sourceable_id')
        ]);

        // Drop polymorphic columns
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['sourceable_type', 'sourceable_id']);
        });
    }
};
