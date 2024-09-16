<?php

namespace App\Services\V3_1;

use App\Models\Order;
use App\Models\Taxonomy;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AppointmentService
{
    public function getAppointments(?Request $request = null): Collection
    {
        $appointments = Vendor::findOrfail($request->vendor_id)->appointmentForm->load('values');

        if ($appointments->isEmpty()) {
            return Taxonomy::findOrfail($request->taxonomy_id)->appointmentForm->load('values');
        }

        return $appointments;
    }

    public function createAppointment(Order|Model $order, array $appointments): void
    {
        foreach ($appointments as $value) {
            $orderAppointments[] = [
                'order_id' => $order->id,
                'appointment_form_id' => $value['appointment_form_id'],
                'appointment_form_value_id' => $value['appointment_form_value_id'],
                'key' => $value['key'],
                'answer' => $value['answer'],
            ];
        }

        $order->appointments()->sync($orderAppointments);
    }
}
