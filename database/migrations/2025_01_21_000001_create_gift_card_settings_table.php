<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('gift_card_settings')) {
            Schema::create('gift_card_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->json('value');
                $table->enum('type', ['string', 'array', 'boolean', 'integer'])->default('string');
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('gift_card_settings');
    }
};
