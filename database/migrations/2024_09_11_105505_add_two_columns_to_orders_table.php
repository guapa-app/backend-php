<?php

use App\Enums\OrderTypeEnum;
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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignIdFor(Vendor::class, 'sub_vendor_id')
                ->constrained('vendors')->nullable()->after('address_id');
            $table->enum('type', OrderTypeEnum::getValues())->after('sub_vendor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeignIdFor(Vendor::class, 'sub_vendor_id');
            $table->dropColumn('type');
        });
    }
};
