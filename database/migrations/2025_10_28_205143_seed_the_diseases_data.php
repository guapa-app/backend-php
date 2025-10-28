<?php

use App\Models\Disease;
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
        $diseases = [
            [
                'name' => [
                    'ar' => 'قصور الغدة الدرقية',
                    'en' => 'Hypothyroidism',
                ],
            ],
            [
                'name' => [
                    'ar' => 'أمراض القلب و الأوعية الدموية',
                    'en' => 'Cardiovascular Diseases',
                ],
            ],
            [
                'name' => [
                    'ar' => 'مرض سيولة الدم',
                    'en' => 'Hemophilia',
                ],
            ],
            [
                'name' => [
                    'ar' => 'السمنة',
                    'en' => 'Obesity',
                ],
            ],
            [
                'name' => [
                    'ar' => 'التهاب المفاصل',
                    'en' => 'Arthritis',
                ],
            ],
            [
                'name' => [
                    'ar' => 'أمراض الجهاز التنفسي',
                    'en' => 'Respiratory Diseases',
                ],
            ],
            [
                'name' => [
                    'ar' => 'السكري',
                    'en' => 'Diabetes',
                ],
            ],
            [
                'name' => [
                    'ar' => 'الضغط',
                    'en' => 'Hypertension',
                ],
            ],
        ];

        foreach ($diseases as $disease) {
            Disease::create($disease);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Disease::truncate();
    }
};
