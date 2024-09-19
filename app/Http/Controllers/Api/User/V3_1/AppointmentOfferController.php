<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\V3_1\AppointmentOfferRequest;
use App\Http\Resources\V3_1\OrderResource;
use App\Http\Resources\V3_1\User\AppointmentOfferResource;
use App\Models\AppointmentOffer;
use App\Models\AppointmentOfferDetail;
use App\Services\V3_1\AppointmentOfferService;
use Illuminate\Http\Request;

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
                ->with('vendor', 'taxonomy', 'appointmentForms')
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
                ->with('vendor', 'taxonomy', 'details.subVendor', 'appointmentForms')
                ->findOrFail($id)
        )->additional([
            'success' => true,
            'message' => __('api.success'),
        ]);
    }

    public function store(AppointmentOfferRequest $request)
    {
        return AppointmentOfferResource::make(
            $this->appointmentOfferService->create($request)
        )->additional([
            'success' => true,
            'message' => __('api.success'),
        ]);
    }

    public function accept(Request $request)
    {
        return OrderResource::make(
            $this->appointmentOfferService->accept(
                AppointmentOfferDetail::findOrfail($request->appointment_offer_detail_id)
            )
        )->additional([
            'success' => true,
            'message' => __('api.success'),
        ]);
    }

    public function reject(Request $request)
    {
        $this->appointmentOfferService->reject(
            AppointmentOfferDetail::findOrfail($request->appointment_offer_detail_id)
        );

        return $this->successJsonRes([], __('api.success'));
    }
}
