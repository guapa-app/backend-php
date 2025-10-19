<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('reviewable_type');
            $table->foreignId('reviewable_id');
            $table->foreignId('user_id');
            $table->tinyInteger('stars')->unsigned();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->index(['reviewable_type', 'reviewable_id', 'user_id'], 'reviews_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
