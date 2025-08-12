<?php

use App\Models\AdminEmail;
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
        $emails = [
            'hiba40096@gmail.com',
            'smosbah70@gmail.com',
            'Info@guapa.com.sa',
            'SRARHALSUYAYFI@GMAIL.COM',
            'abdulaziz@guapa.com',
            'milafalsahabi@gmail.com'
        ];

        foreach ($emails as $email) {
            AdminEmail::create([
                'email' => $email
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        AdminEmail::truncate();
    }
};
