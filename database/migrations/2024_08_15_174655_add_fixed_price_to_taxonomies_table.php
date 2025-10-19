<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('taxonomies', function (Blueprint $table) {
            $table->string('fixed_price')
                ->nullable()
                ->after('fees');

            $table->decimal('fees', 5, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('taxonomies', function (Blueprint $table) {
            $table->dropColumn('fixed_price');
        });
    }
};
