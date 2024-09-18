<?php

use App\Enums\AppointmentOfferEnum;
use App\Models\Taxonomy;
use App\Models\User;
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
        Schema::create('appointment_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Vendor::class)->constrained();
            $table->foreignIdFor(Taxonomy::class)->constrained();
            $table->enum('status', AppointmentOfferEnum::getValues())->default(AppointmentOfferEnum::Pending->value);
            $table->decimal('total')->default(0);
            $table->text('notes')->nullable();
            $table->string('invoice_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_offers');
    }
};
