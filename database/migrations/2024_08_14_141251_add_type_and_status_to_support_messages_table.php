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
        Schema::table('support_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('support_message_type_id')
                ->after('user_id')
                ->nullable();
            $table->char('status', '12')
                ->after('body')
                ->nullable();
        });

        Schema::table('support_messages', function (Blueprint $table) {
            $table->foreign('support_message_type_id')
                ->references('id')
                ->on('support_message_types')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('support_messages', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('support_message_type_id');
        });
    }
};
