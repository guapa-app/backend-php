<?php

namespace App\Services\V3_1;

use App\Models\Order;
use App\Models\Taxonomy;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AppointmentFormService
{
    public function get(?Request $request = null): Collection
    {
        $appointments = Vendor::findOrfail($request->vendor_id)->appointmentForms->load('values');

        if ($appointments->isEmpty()) {
            return Taxonomy::findOrfail($request->taxonomy_id)->appointmentForms->load('values');
        }

        return $appointments;
    }

    public function create(Model $model, array $data, array $additionalParameters): void
    {
        foreach ($data as $value) {
            $appointmentsArr[] = [
                    'appointment_form_id' => $value['appointment_form_id'],
                    'appointment_form_value_id' => $value['appointment_form_value_id'],
                    'key' => $value['key'],
                    'answer' => $value['answer'],
                ] + $additionalParameters;
        }

        $model->appointmentForms()->sync($appointmentsArr);
    }
}
