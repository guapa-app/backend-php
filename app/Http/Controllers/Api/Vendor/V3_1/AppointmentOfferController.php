<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\V3_1\AppointmentOfferRequest;
use App\Http\Resources\V3_1\Vendor\AppointmentOfferResource;
use App\Models\AppointmentOffer;
use App\Services\V3_1\AppointmentOfferService;

class AppointmentOfferController extends BaseApiController
{
    public function __construct(public AppointmentOfferService $appointmentOfferService)
    {
        parent::__construct();
    }

    public function index()
    {
        return AppointmentOfferResource::collection(
            $this->user->appointmentOffers()
                ->with('user', 'taxonomy', 'appointmentForms')
                ->latest('id')
                ->paginate()
        )->additional([
            'success' => true,
            'message' => __('api.success'),
        ]);
    }

    public function show(int $id)
    {
        return AppointmentOfferResource::make(
            AppointmentOffer::query()
                ->with('user', 'taxonomy', 'appointmentForms', 'media')
                ->findOrFail($id)
        )->additional([
            'success' => true,
            'message' => __('api.success'),
        ]);
    }

    public function store(AppointmentOfferRequest $request): AppointmentOfferResource
    {
        return AppointmentOfferResource::make(
            $this->appointmentOfferService->create($request)
        )->additional([
            'success' => true,
            'message' => __('api.success'),
        ]);
    }
}
