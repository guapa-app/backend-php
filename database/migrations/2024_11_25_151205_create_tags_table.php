<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->timestamps();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('tag_id')
                ->after('category_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            DB::beginTransaction();
            $table->dropForeign('posts_tag_id_foreign');
            $table->dropIndex('posts_tag_id_foreign');
            $table->dropColumn('tag_id');
            DB::commit();
        });

        Schema::dropIfExists('tags');
    }
};
