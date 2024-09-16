<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\V3_1\AppointmentFormResource;
use App\Services\V3_1\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends BaseApiController
{
    public function __construct(public AppointmentService $appointmentService)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        return AppointmentFormResource::collection($this->appointmentService->getAppointments($request));
    }
}
