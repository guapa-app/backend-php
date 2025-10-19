<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('support_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')
                ->after('id')
                ->nullable();
            $table->char('sender_type', 12)
                ->after('user_id')
                ->nullable();
            $table->unsignedBigInteger('support_message_type_id')
                ->after('sender_type')
                ->nullable();
            $table->char('status', '12')
                ->after('subject')
                ->nullable();
            $table->string('phone', 30)->nullable()->change();
        });

        Schema::table('support_messages', function (Blueprint $table) {
            $table->foreign('parent_id')
                ->references('id')
                ->on('support_messages')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
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
            DB::beginTransaction();
            $table->dropColumn('status');

            $table->dropForeign('support_messages_support_message_type_id_foreign');
            $table->dropIndex('support_messages_support_message_type_id_foreign');
            $table->dropColumn('support_message_type_id');

            $table->dropColumn('sender_type');

            $table->dropForeign('support_messages_parent_id_foreign');
            $table->dropIndex('support_messages_parent_id_foreign');
            $table->dropColumn('parent_id');
            DB::commit();
        });
    }
};
