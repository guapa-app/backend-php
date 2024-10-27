<?php

use App\Models\AppointmentForm;
use App\Models\AppointmentOffer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointment_offer_form', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AppointmentOffer::class)->constrained();
            $table->foreignIdFor(AppointmentForm::class)->constrained();
            $table->string('key')->nullable();
            $table->longText('answer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_offer_form');
    }
};
