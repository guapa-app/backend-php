<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddCancellationReasonToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            DB::statement("alter table orders
                                    modify status enum ('Pending', 'Accepted', 'Rejected', 'Cancel Request', 'Canceled') default 'Pending' not null,
                                    add cancellation_reason text null after invoice_url,
                                    modify created_at timestamp null after cancellation_reason,
                                    modify updated_at timestamp null after created_at;
            ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            DB::statement("update orders set status = 'Pending' where status = 'Cancel Request'");
            DB::statement("alter table orders
                                    modify status enum ('Pending', 'Accepted', 'Rejected', 'Canceled') default 'Pending' not null,
                                    drop cancellation_reason;
            ");
        });
    }
}
