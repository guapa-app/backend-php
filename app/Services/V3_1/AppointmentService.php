<?php

namespace App\Services\V3_1;

use App\Models\Taxonomy;
use App\Models\Vendor;
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
}
