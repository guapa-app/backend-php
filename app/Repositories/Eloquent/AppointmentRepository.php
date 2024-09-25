<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\AppointmentOfferRepositoryInterface;
use App\Http\Requests\V3_1\Common\AppointmentOfferRequest;
use App\Models\AppointmentOffer;
use App\Models\AppointmentOfferDetail;
use App\Models\Order;
use App\Services\V3_1\AppointmentOfferService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AppointmentRepository implements AppointmentOfferRepositoryInterface
{
    public function __construct(public AppointmentOfferService $appointmentOfferService)
    {
    }

    public function index(): LengthAwarePaginator
    {
        return $this->appointmentOfferService->index()->latest('id')->paginate();
    }

    public function show(int $id): AppointmentOffer|Model
    {
        return AppointmentOffer::query()
            ->with('vendor.logo', 'taxonomy', 'details.subVendor', 'appointmentForms.values', 'media')
            ->findOrFail($id);
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
