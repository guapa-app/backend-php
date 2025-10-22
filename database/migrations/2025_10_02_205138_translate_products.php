<?php

use App\Models\Product;
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
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('title', 'title_old');
            $table->renameColumn('description', 'description_old');
            $table->renameColumn('terms', 'terms_old');
        });

        // // Step 2: Create new JSON column
        Schema::table('products', function (Blueprint $table) {
            $table->json('title')->nullable()->after('title_old');
            $table->json('description')->nullable()->after('description_old');
            $table->json('terms')->nullable()->after('terms_old');
        });

        // Step 3: Migrate existing data into JSON
        DB::table('products')->orderBy('id')->chunkById(100, function ($products) {
            foreach ($products as $product) {
                DB::table('products')
                    ->where('id', $product->id)
                    ->update([
                            'title' => json_encode(['en' => $product->title_old, 'ar' => $product->title_old]),
                            'description' => json_encode(['en' => $product->description_old, 'ar' => $product->description_old]),
                            'terms' => json_encode(['en' => $product->terms_old, 'ar' => $product->terms_old]),
                        ]);
            }
        });

        // Step 4: Drop old columns
        Schema::table('products', function (Blueprint $table) {
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
        Schema::table('products', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('terms')->nullable();
        });
    }
};
