<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\V3_1\AppointmentFormResource;
use App\Services\V3_1\AppointmentFormService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AppointmentFormController extends BaseApiController
{
    public function __construct(public AppointmentFormService $appointmentService)
    {
        parent::__construct();
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return AppointmentFormResource::collection($this->appointmentService->get($request))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
