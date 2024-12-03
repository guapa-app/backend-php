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
        Schema::table('reviews', function (Blueprint $table) {
//            $table->foreignId('order_id')->after('id')->constrained()->cascadeOnDelete();
            $table->boolean('show')->default(false)->after('stars');
            // change stars to decimal
            $table->decimal('stars', 2, 1)->change();

            // Drop morphs if exists
            if (Schema::hasColumn('reviews', 'reviewable_id') && Schema::hasColumn('reviews', 'reviewable_type')) {
                $table->dropIndex('reviews_index'); // Drop the composite index
                $table->dropColumn(['reviewable_id', 'reviewable_type']); // Drop the columns
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropConstrainedForeignId('order_id');

            // Add morphs if not exists
            if (!Schema::hasColumns('reviews', ['reviewable_id', 'reviewable_type'])) {
                $table->morphs('reviewable');
            }
        });
    }
};
