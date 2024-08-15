<?php

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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 12)->unique();
            $table->tinyInteger('discount_percentage');
            $table->enum('discount_source', ['vendor','app','both'])->default('vendor');
            $table->timestamp('expires_at')->nullable();
            $table->integer('max_uses')->unsigned()->nullable();
            $table->integer('single_user_usage')->default(1);
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete()->comment('The admin who created the coupon');
            $table->softDeletes();
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
