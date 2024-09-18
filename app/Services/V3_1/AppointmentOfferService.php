<?php

namespace App\Services\V3_1;

use App\Enums\AppointmentOfferEnum;
use App\Http\Requests\V3_1\AppointmentOfferRequest;
use App\Models\AppointmentOffer;
use App\Models\AppointmentOfferDetail;
use App\Models\Taxonomy;
use App\Services\MediaService;
use App\Services\PaymentService;
use Illuminate\Support\Facades\DB;

class AppointmentOfferService
{
    public function create(AppointmentOfferRequest $request): AppointmentOffer
    {
        if (!$request->has('appointment_offer_id')) {
            return DB::transaction(function () use ($request) {
                $appointmentOffer = AppointmentOffer::create([
                    'user_id' => auth('api')->user()->id,
                    'vendor_id' => $request->vendor_id,
                    'taxonomy_id' => $request->taxonomy_id,
                    'notes' => $request->notes,
                    'status' => AppointmentOfferEnum::Pending->value
                ]);

                $transformedArray = array_map(function ($value) {
                    return ['sub_vendor_id' => $value];
                }, $request->sub_vendor_ids);
                $appointmentOffer->details()->createMany($transformedArray);

                (new AppointmentFormService())->create(
                    $appointmentOffer,
                    $request->validated()['appointments'],
                    ['appointment_offer_id' => $appointmentOffer->id]
                );

                if (isset($request->media)) {
                    $this->updateMedia($appointmentOffer, $request->validated());
                }

                /* Create new invoice for the appointment offer */
                $appointmentPrice = Taxonomy::find($request->taxonomy_id)?->appointment_price;
                $invoice = (new PaymentService())->generateInvoice($appointmentOffer, "Appointment app fees",
                    $appointmentPrice, 0);
                $appointmentOffer->update(['invoice_url' => $invoice->url]);

                return $this->loadAppointmentOffer($appointmentOffer);
            });
        }

        $appointmentOffer = AppointmentOffer::find($request->appointment_offer_id);
        $appointmentOfferDetails = $appointmentOffer
            ->details()
            ->where('sub_vendor_id', $request->sub_vendor_id)
            ->first();

        switch ($request->status) {
            case AppointmentOfferEnum::Accept->value:
                $this->update($request, $appointmentOfferDetails);
                break;
            case AppointmentOfferEnum::Reject->value:
                $appointmentOfferDetails->update([
                    'status' => $request->status,
                    'reject_reason' => $request->reject_reason
                ]);
                break;
        }

        return $this->loadAppointmentOffer($appointmentOffer);
    }

    public function reject(AppointmentOfferDetail $appointmentOfferDetail): void
    {
        $appointmentOfferDetail->update(['status' => AppointmentOfferEnum::Reject->value]);
    }

    /**
     * Update appointment offer.
     *
     * @param  AppointmentOfferRequest  $request
     * @param  AppointmentOfferDetail  $appointmentOfferDetails
     *
     * @return void
     */
    public function update(AppointmentOfferRequest $request, AppointmentOfferDetail $appointmentOfferDetails): void
    {
        $appointmentOfferDetails->update([
            'status' => $request->status,
            'staff_notes' => $request->staff_notes,
            'offer_notes' => $request->offer_notes,
            'terms' => $request->terms,
            'offer_price' => $request->offer_price,
            'starts_at' => $request->starts_at,
            'expires_at' => $request->expires_at,
        ]);
    }

    /**
     * Load appointment offer with required relations.
     *
     * @param  AppointmentOffer  $appointmentOffer
     *
     * @return AppointmentOffer
     */
    public function loadAppointmentOffer(AppointmentOffer $appointmentOffer): AppointmentOffer
    {
        return $appointmentOffer->load('vendor', 'taxonomy', 'details.subVendor', 'appointmentForms', 'media');
    }

    /**
     * Update AppointmentOffer media.
     *
     * @param  AppointmentOffer  $appointmentOffer
     * @param  array  $data
     *
     * @return AppointmentOffer
     */
    public function updateMedia(AppointmentOffer $appointmentOffer, array $data): AppointmentOffer
    {
        (new MediaService())->handleMedia($appointmentOffer, $data);

        $appointmentOffer->load('media');

        return $appointmentOffer;
    }
}
