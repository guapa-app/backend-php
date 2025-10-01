<?php

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
        // Step 1: Rename column
        Schema::table('vendors', function (Blueprint $table) {
            $table->renameColumn('name', 'name_old');
            $table->renameColumn('about', 'about_old');
        });

        // // Step 2: Create new JSON column
        Schema::table('vendors', function (Blueprint $table) {
            $table->json('name')->nullable()->after('email');
            $table->json('about')->nullable()->after('iban');
        });

        // Step 3: Migrate existing data into JSON
        Vendor::withTrashed()->get()->each(function ($vendor) {
            $vendor->name = [
                'en' => $vendor->name_old,
                'ar' => $vendor->name_old,
            ];

            $vendor->about = [
                'en' => $vendor->about_old,
                'ar' => $vendor->about_old,
            ];

            $vendor->save();
        });

        // Step 4: Drop old columns
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('name_old');
            $table->dropColumn('about_old');
        });
    }

    public function down(): void
    {
        // Rollback: create varchar again
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->text('about')->nullable();
        });
    }
};
