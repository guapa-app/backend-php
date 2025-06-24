<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('gift_cards', function (Blueprint $table) {
            $table->enum('product_type', ['product', 'service'])->nullable()->after('type');
        });
    }

    public function down()
    {
        Schema::table('gift_cards', function (Blueprint $table) {
            $table->dropColumn('product_type');
        });
    }
};
