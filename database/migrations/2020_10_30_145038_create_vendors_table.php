<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('email');
            $table->string('phone');
            $table->tinyInteger('type')->unsigned()->default(0);

            $table->enum('status', ['0', '1'])->default('1');
            $table->boolean('verified')->default(0);
            $table->text('about')->nullable();

            $table->string('working_days')->nullable();
            $table->string('working_hours')->nullable();

            $table->string('whatsapp')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('snapchat')->nullable();

            $table->string('website_url')->nullable();
            $table->string('known_url')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('cat_number')->nullable();
            $table->string('reg_number')->nullable();
            $table->string('health_declaration')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}
