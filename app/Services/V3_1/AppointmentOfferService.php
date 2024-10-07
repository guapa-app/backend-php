<?php

namespace App\Services\V3_1;

use App\Enums\AppointmentOfferEnum;
use App\Enums\OrderTypeEnum;
use App\Http\Requests\V3_1\Common\AppointmentOfferRequest;
use App\Models\AppointmentOffer;
use App\Models\AppointmentOfferDetail;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Taxonomy;
use App\Models\User;
use App\Models\UserVendor;
use App\Notifications\AppointmentOfferNotification;
use App\Services\MediaService;
use App\Services\PaymentService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AppointmentOfferService
{
    public function index()
    {
        $user = auth('api')->user();

        if ($user->vendor) {
            $vendor = $user->vendor;

          $appointmentOffers = AppointmentOffer::query()
                ->whereHas('details', function ($query) use ($vendor) {
                    $query->where('vendor_id', $vendor->id);
                });

            return $appointmentOffers->with('user', 'taxonomy', 'appointmentForms');
        }

        return $user->appointmentOffers()->with('vendors', 'taxonomy', 'appointmentForms');
    }

    public function create(AppointmentOfferRequest $request): AppointmentOffer
    {
        if (!$request->has('appointment_offer_id')) {
            return DB::transaction(function () use ($request) {
                $appointmentOffer = AppointmentOffer::create([
                    'user_id' => auth('api')->user()->id,
                    'taxonomy_id' => $request->taxonomy_id,
                    'notes' => $request->notes,
                    'status' => AppointmentOfferEnum::Pending->value
                ]);

                $transformedArray = array_map(function ($value) {
                    return ['vendor_id' => $value, 'status' => AppointmentOfferEnum::Pending->value];
                }, $request->vendor_ids);
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
                $taxes = (Setting::getTaxes() / 100) * $appointmentPrice;
                $invoice = (new PaymentService())->generateInvoice($appointmentOffer, "Appointment app fees",
                    $appointmentPrice, $taxes);
                $application_fees  = $appointmentPrice + $taxes;

                $appointmentOffer->update([
                    'invoice_url' => $invoice->url,
                    'application_fees' => $application_fees
                ]);

                return $this->loadAppointmentOffer($appointmentOffer);
            });
        }
        $appointmentOffer = AppointmentOffer::find($request->appointment_offer_id);
        $appointmentOfferDetails = $appointmentOffer
            ->details()
            ->where('vendor_id', auth('api')->user()->vendor->id)
            ->firstOrFail();

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

    public function accept(AppointmentOfferDetail $appointmentOfferDetail): Order
    {
        return DB::transaction(function () use ($appointmentOfferDetail) {
            $appointmentOffer = $appointmentOfferDetail->appointmentOffer;

            $description = "Order invoice";
            $taxes = (Setting::getTaxes() / 100) * $appointmentOfferDetail->offer_price;
            $fees = $this->calculateOrderFees($appointmentOffer, 'taxonomy', $appointmentOfferDetail->offer_price);
            $total = $appointmentOfferDetail->offer_price + $taxes + $fees;

            $order = $appointmentOfferDetail->order()->create([
                'user_id' => $appointmentOffer->user_id,
                'vendor_id' => $appointmentOfferDetail->vendor_id,
                'type' => OrderTypeEnum::Appointment->value,
                'total' => $total
            ]);

            // Generate invoice for the order
            $invoice = (new PaymentService())->generateInvoice($order, $description, $fees, $taxes);
            $order->invoice_url = $invoice->url;
            $order->save();

            $appointmentOffer->update([
                'total' => $total
            ]);

            return $order;
        });
    }

    public function reject(AppointmentOfferDetail $appointmentOfferDetail): void
    {
        $appointmentOfferDetail->update(['status' => AppointmentOfferEnum::Reject->value]);
    }

    private function calculateOrderFees(Model $model, string $relation, float $price)
    {
        $taxonomy = $model->$relation()->first();

        if ($taxonomy?->fees) {
            return ($taxonomy->fees / 100) * $price;
        } else {
            return $taxonomy?->fixed_price ?? 0;
        }
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
        return $appointmentOffer->load('details','taxonomy', 'appointmentForms', 'media');
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

    /**
     * change payment status
     *
     * @param  array $data
     *
     */
    public function changePaymentStatus(array $data) : void
    {
        $appointmentOffer = AppointmentOffer::findOrfail($data['id']);
        if ($data['status'] == 'paid') {
            $appointmentOffer->status =  AppointmentOfferEnum::Paid_Application_Fees->value;
            $appointmentOffer->payment_id = $data['payment_id'];
            $appointmentOffer->payment_gateway = $data['payment_gateway'];
            $appointmentOffer->save();

            // Send email notifications
            $userVendors = UserVendor::query()
                ->whereIn('vendor_id', $appointmentOffer->details->pluck('vendor_id'))
                ->pluck('user_id');
            $users = User::whereIn('id', $userVendors)->get();
            Notification::send($users, new AppointmentOfferNotification($appointmentOffer));
        }else {
            $appointmentOffer->status = $data['status'];
            $appointmentOffer->save();
        }

    }
}
