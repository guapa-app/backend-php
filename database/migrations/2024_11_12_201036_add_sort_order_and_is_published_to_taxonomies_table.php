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
        Schema::table('taxonomies', function (Blueprint $table) {
            $table->integer('sort_order')
                ->after('parent_id')
                ->nullable();
            $table->boolean('is_published')
                ->after('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('taxonomies', function (Blueprint $table) {
            $table->dropColumn('sort_order');
            $table->dropColumn('is_published');
        });
    }
};
