<?php

use App\Enums\AppointmentOfferEnum;
use App\Models\AppointmentOffer;
use App\Models\Vendor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointment_offer_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AppointmentOffer::class)->constrained();
            $table->foreignIdFor(Vendor::class, 'vendor_id')
                ->constrained('vendors'); // here we assign it to sub vendor
            $table->char('status', 30);
            $table->decimal('offer_price')->default(0);
            $table->text('reject_reason')->nullable();
            $table->text('staff_notes')->nullable();
            $table->text('offer_notes')->nullable();
            $table->text('terms')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_offer_details');
    }
};
