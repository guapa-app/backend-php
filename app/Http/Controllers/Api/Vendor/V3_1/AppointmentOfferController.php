<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Contracts\Repositories\AppointmentOfferRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\V3_1\Vendor\AcceptAppointmentRequest;
use App\Http\Resources\Vendor\V3_1\AppointmentOfferCollection;
use App\Http\Resources\Vendor\V3_1\AppointmentOfferDetailsResource;
use App\Http\Resources\Vendor\V3_1\AppointmentOfferResource;
use App\Services\V3_1\AppointmentOfferService;
use Illuminate\Http\Request;

class AppointmentOfferController extends BaseApiController
{
    protected $appointmentOfferRepository;
    protected $appointmentOfferService;
    public function __construct(
        AppointmentOfferRepositoryInterface $appointmentOfferRepository,
        AppointmentOfferService $appointmentOfferService
    )
    {
        parent::__construct();
        $this->appointmentOfferRepository = $appointmentOfferRepository;
        $this->appointmentOfferService = $appointmentOfferService;
    }

    public function index(Request $request): AppointmentOfferCollection
    {
        return AppointmentOfferCollection::make($this->appointmentOfferRepository->all($request))
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

    public function approveAppointmentOffer(AcceptAppointmentRequest $request): AppointmentOfferDetailsResource
    {
        $data = $request->validated();
        // temporary fix for the terms translation
        if(isset($data['terms'])){
            $data['terms'] = [
                'en' => $data['terms'],
                'ar' => $data['terms'],
            ];
        }

        return AppointmentOfferDetailsResource::make($this->appointmentOfferService->approveAppointmentOffer($data))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
