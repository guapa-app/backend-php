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
        // Step 1: Rename column
        Schema::table('appointment_offer_details', function (Blueprint $table) {
            $table->renameColumn('terms', 'terms_old');
        });

        // // Step 2: Create new JSON column
        Schema::table('appointment_offer_details', function (Blueprint $table) {
            $table->json('terms')->nullable()->after('terms_old');
        });

        // Step 3: Migrate existing data into JSON
        DB::table('appointment_offer_details')->orderBy('id')->chunkById(100, function ($appointmentOfferDetails) {
            foreach ($appointmentOfferDetails as $appointmentOfferDetail) {
                DB::table('appointment_offer_details')
                    ->where('id', $appointmentOfferDetail->id)
                    ->update([
                            'terms' => json_encode(['en' => $appointmentOfferDetail->terms_old, 'ar' => $appointmentOfferDetail->terms_old]),
                        ]);
            }
        });

        // Step 4: Drop old columns
        Schema::table('appointment_offer_details', function (Blueprint $table) {
            $table->dropColumn('terms_old');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback: create varchar again
        Schema::table('appointment_offer_details', function (Blueprint $table) {
            $table->text('terms')->nullable();
        });
    }
};
