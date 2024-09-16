<?php

use App\Models\AppointmentForm;
use App\Models\Vendor;
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
        Schema::create('appointment_form_vendor', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AppointmentForm::class)->constrained();
            $table->foreignIdFor(Vendor::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_form_vendor');
    }
};
