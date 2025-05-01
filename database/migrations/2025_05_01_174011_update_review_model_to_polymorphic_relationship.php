<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Add polymorphic columns
            $table->nullableMorphs('reviewable');
        });

        // Migrate existing data - map order_id to reviewable
        DB::table('reviews')->whereNotNull('order_id')->update([
            'reviewable_type' => 'App\\Models\\Order',
            'reviewable_id' => DB::raw('order_id')
        ]);

        // Drop the old order_id column
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropConstrainedForeignId('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Add back the order_id column
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
        });

        // Migrate data back
        DB::table('reviews')->where('reviewable_type', 'App\\Models\\Order')->update([
            'order_id' => DB::raw('reviewable_id')
        ]);

        // Drop polymorphic columns
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['reviewable_type', 'reviewable_id']);
        });
    }
};