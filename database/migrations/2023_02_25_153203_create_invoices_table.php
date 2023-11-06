<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');

            $table->char('status', '12');
            $table->integer('amount');
            $table->char('currency', 5);
            $table->string('description');
            $table->string('callback_url');

            $table->string('invoice_id')->nullable();
            $table->char('amount_format')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('url')->nullable();

            $table->timestamps();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
