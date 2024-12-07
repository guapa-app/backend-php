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
        Schema::create('category_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('taxonomies')->onDelete('cascade');
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->enum('fee_option', ['percentage', 'fixed']);
            $table->decimal('fee_percentage', 5, 2)->nullable();
            $table->decimal('fee_fixed', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_fees');
    }
};
