<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\AppointmentOfferRepositoryInterface;
use App\Http\Requests\V3_1\Common\AppointmentOfferRequest;
use App\Models\AppointmentOffer;
use App\Models\AppointmentOfferDetail;
use App\Models\Order;
use App\Services\V3_1\AppointmentOfferService;
use Illuminate\Http\Request;

class AppointmentRepository extends EloquentRepository implements AppointmentOfferRepositoryInterface
{
    public $perPage = 10;

    public function __construct(public AppointmentOfferService $appointmentOfferService, AppointmentOffer $model)
    {
        parent::__construct($model);
    }

    public function all(Request $request): object
    {
        $perPage = (int) ($request->has('perPage') ? $request->get('perPage') : $this->perPage);

        if ($perPage > 50) {
            $perPage = 50;
        }

        $query = $this->appointmentOfferService->index()->latest('id');

        if ($request->has('perPage')) {
            return $query->paginate($perPage);
        }
        return $query->get();
    }

    public function store(AppointmentOfferRequest $request): AppointmentOffer
    {
        return $this->appointmentOfferService->create($request);
    }

    public function accept(Request $request): Order
    {
        return $this->appointmentOfferService->accept(
            AppointmentOfferDetail::findOrfail($request->appointment_offer_detail_id)
        );
    }

    public function reject(Request $request): void
    {
        $this->appointmentOfferService->reject(
            AppointmentOfferDetail::findOrfail($request->appointment_offer_detail_id)
        );
    }
}
