<?php

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Invoice::get()->map(function ($invoice) {
            $invoice->update([
                'invoiceable_id' => $invoice->order_id,
                'invoiceable_type' => (new \ReflectionClass(Order::class))->getName(),
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
