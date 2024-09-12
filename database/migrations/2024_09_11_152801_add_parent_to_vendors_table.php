<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')
                ->after('id')
                ->nullable();
        });
        Schema::table('vendors', function (Blueprint $table) {
            $table->foreign('parent_id')
                ->references('id')
                ->on('vendors')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            DB::beginTransaction();
            $table->dropForeign('vendors_parent_id_foreign');
            $table->dropIndex('vendors_parent_id_foreign');
            $table->dropColumn('parent_id');
            DB::commit();
        });
    }
};
