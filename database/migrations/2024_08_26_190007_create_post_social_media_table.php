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
        Schema::create('post_social_media', function (Blueprint $table) {
            $table->foreignId('post_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('social_media_id')
                ->constrained()
                ->onDelete('cascade');

            $table->text('link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_social_media');
    }
};
