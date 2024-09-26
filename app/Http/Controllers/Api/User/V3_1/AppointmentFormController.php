<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\User\V3_1\AppointmentFormCollection;
use App\Services\V3_1\AppointmentFormService;
use Illuminate\Http\Request;

class AppointmentFormController extends BaseApiController
{
    public function __construct(public AppointmentFormService $appointmentService)
    {
        parent::__construct();
    }

    public function index(Request $request): AppointmentFormCollection
    {
        return AppointmentFormCollection::make($this->appointmentService->get($request))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
