<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Contracts\Repositories\AppointmentOfferRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\V3_1\User\AppointmentOfferRequest;
use App\Http\Resources\User\V3_1\AppointmentOfferCollection;
use App\Http\Resources\User\V3_1\AppointmentOfferResource;
use App\Http\Resources\User\V3_1\OrderResource;
use App\Services\V3_1\AppointmentOfferService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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

    public function store(AppointmentOfferRequest $request): AppointmentOfferResource
    {
        return AppointmentOfferResource::make($this->appointmentOfferService->create($request))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function accept(Request $request): OrderResource
    {
        $request->validate([
            'appointment_offer_detail_id' => 'required|integer|exists:appointment_offer_details,id',
        ]);

        return OrderResource::make($this->appointmentOfferService->accept($request->appointment_offer_detail_id))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function reject(Request $request): JsonResponse
    {
        $request->validate([
            'appointment_offer_detail_id' => 'required|integer|exists:appointment_offer_details,id',
        ]);
        $this->appointmentOfferService->reject($request->appointment_offer_detail_id);

        return $this->successJsonRes([], __('api.success'));
    }
}
