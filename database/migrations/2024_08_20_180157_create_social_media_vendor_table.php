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
        Schema::create('social_media_vendor', function (Blueprint $table) {
            $table->foreignId('social_media_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('vendor_id')
                ->constrained()
                ->onDelete('cascade');

            $table->text('link');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media_vendor');
    }
};
