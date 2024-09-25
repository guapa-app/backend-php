<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Contracts\Repositories\AppointmentOfferRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\V3_1\Common\AppointmentOfferRequest;
use App\Http\Resources\Vendor\V3_1\AppointmentOfferResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AppointmentOfferController extends BaseApiController
{
    public function __construct(public AppointmentOfferRepositoryInterface $appointmentOfferRepository)
    {
        parent::__construct();
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return AppointmentOfferResource::collection($this->appointmentOfferRepository->all($request))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function show(int $id): AppointmentOfferResource
    {
        return AppointmentOfferResource::make($this->appointmentOfferRepository->getOneWithRelations($id))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function store(AppointmentOfferRequest $request): AppointmentOfferResource
    {
        return AppointmentOfferResource::make($this->appointmentOfferRepository->store($request))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
