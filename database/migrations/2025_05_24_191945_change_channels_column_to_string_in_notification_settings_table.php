<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, convert existing JSON arrays to single strings
        $settings = DB::table('notification_settings')->get();
        foreach ($settings as $setting) {
            $channels = json_decode($setting->channels, true);
            if (is_array($channels) && count($channels) > 0) {
                // Take the first channel from the array
                $singleChannel = $channels[0];
                DB::table('notification_settings')
                    ->where('id', $setting->id)
                    ->update(['channels' => $singleChannel]);
            }
        }

        // Then change the column type from JSON to VARCHAR
        Schema::table('notification_settings', function (Blueprint $table) {
            $table->string('channels', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert strings back to JSON arrays and change column type back to JSON
        Schema::table('notification_settings', function (Blueprint $table) {
            $table->json('channels')->change();
        });

        // Convert single string channels back to JSON arrays
        $settings = DB::table('notification_settings')->get();
        foreach ($settings as $setting) {
            $channelArray = [$setting->channels];
            DB::table('notification_settings')
                ->where('id', $setting->id)
                ->update(['channels' => json_encode($channelArray)]);
        }
    }
};
