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
        Schema::create('wallet_charging_packages', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->decimal('amount', 10, 2); // Amount of money to charge
            $table->integer('points'); // Points associated with this package
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_charging_packages');
    }
};
