<?php

use App\Models\Offer;
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
        Schema::table('offers', function (Blueprint $table) {
            $table->renameColumn('title', 'title_old');
            $table->renameColumn('description', 'description_old');
            $table->renameColumn('terms', 'terms_old');
        });

        // // Step 2: Create new JSON column
        Schema::table('offers', function (Blueprint $table) {
            $table->json('title')->nullable()->after('discount');
            $table->json('description')->nullable()->after('title_old');
            $table->json('terms')->nullable()->after('description_old');
        });

        // Step 3: Migrate existing data into JSON
        DB::table('offers')
            ->orderBy('id')
            ->chunkById(100, function ($offers) {
                foreach ($offers as $offer) {
                    DB::table('offers')
                        ->where('id', $offer->id)
                        ->update([
                                'title' => json_encode([
                                    'en' => $offer->title_old,
                                    'ar' => $offer->title_old,
                                ]),
                                'description' => json_encode([
                                    'en' => $offer->description_old,
                                    'ar' => $offer->description_old,
                                ]),
                                'terms' => json_encode([
                                    'en' => $offer->terms_old,
                                    'ar' => $offer->terms_old,
                                ]),
                            ]);
                }
            });

        // Step 4: Drop old columns
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn('title_old');
            $table->dropColumn('description_old');
            $table->dropColumn('terms_old');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback: create varchar again
        Schema::table('offers', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('terms')->nullable();
        });
    }
};
