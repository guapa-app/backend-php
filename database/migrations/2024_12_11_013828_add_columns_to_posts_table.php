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
        Schema::table('posts', function (Blueprint $table) {
            $table->string('type')->nullable()->default('blog')->after('id');
            $table->foreignId('product_id')->nullable()->after('category_id')
                ->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->after('admin_id')->constrained();
            $table->boolean('show_user')->default(1)->after('user_id');
            $table->unsignedTinyInteger('stars')->nullable()->after('product_id');
            $table->date('service_date')->nullable()->after('youtube_url');

            // make title column nullable
            $table->string('title')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            //
        });
    }
};
