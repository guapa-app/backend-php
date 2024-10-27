<?php

namespace Database\Seeders;

use App\Enums\AppointmentTypeEnum;
use App\Models\AppointmentForm;
use App\Models\Taxonomy;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['key' => 'الوزن', 'type' => AppointmentTypeEnum::SmallText],
            ['key' => 'الطول', 'type' => AppointmentTypeEnum::SmallText],
            ['key' => 'اسماء الادوية', 'type' => AppointmentTypeEnum::LargeText],
        ];
        AppointmentForm::insert($data);

        $array = [
            ['key' => 'هل يوجد امراض مزمنه', 'type' => AppointmentTypeEnum::SingleCheck],
            ['key' => 'هل تم عمليات جراحيه سابقه', 'type' => AppointmentTypeEnum::SingleCheck],
        ];

        foreach ($array as $item) {
            $appointment = AppointmentForm::create([
                'key' => $item['key'],
                'type' => $item['type'],
            ]);

            $appointment->values()->createMany([
                ['value' => 'نعم'],
                ['value' => 'لا']
            ]);

            $appointment->taxonomies()->sync([Taxonomy::first()->id]);

            $appointment->vendors()->sync(Vendor::first()->id);
        }

        Taxonomy::first()->update(['is_appointment' => true]);
    }
}
