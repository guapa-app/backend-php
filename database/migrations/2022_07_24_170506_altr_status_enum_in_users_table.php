<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AltrStatusEnumInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('" . \App\Models\User::STATUS_ACTIVE . "', '" . \App\Models\User::STATUS_CLOSED . "', '" . \App\Models\User::STATUS_DELETED . "') NOT NULL DEFAULT '" . \App\Models\User::STATUS_ACTIVE . "'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('" . \App\Models\User::STATUS_ACTIVE . "', '" . \App\Models\User::STATUS_CLOSED . "') NOT NULL DEFAULT '" . \App\Models\User::STATUS_ACTIVE . "'");
        });
    }
}
