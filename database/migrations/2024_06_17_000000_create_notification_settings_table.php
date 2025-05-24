<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->string('notification_module');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->json('channels');
            $table->boolean('created_by_super_admin')->default(false);
            $table->boolean('is_global')->default(false);
            $table->timestamps();
            $table->unique(['notification_module', 'admin_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notification_settings');
    }
};
