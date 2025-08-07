<?php

namespace App\Services\V3_1;

use App\Models\User;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Taxonomy;
use App\Models\UserVendor;
use App\Enums\OrderTypeEnum;
use App\Services\MediaService;
use App\Services\QrCodeService;
use App\Services\WalletService;
use App\Models\AppointmentOffer;
use App\Services\PaymentService;
use Illuminate\Support\Facades\DB;
use App\Enums\AppointmentOfferEnum;
use Illuminate\Support\Facades\Log;
use App\Models\AppointmentOfferDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use App\Notifications\AppointmentOfferNotification;
use App\Http\Requests\V3_1\User\AppointmentOfferRequest;

class AppointmentOfferService
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

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
    public function approveAppointmentOffer(array $data): AppointmentOfferDetail
    {
        $appointmentOffer = AppointmentOffer::findOrFail($data['appointment_offer_id']);
        $appointmentOfferDetails = $appointmentOffer
            ->details()
            ->where('vendor_id', auth('api')->user()->vendor->id)
            ->firstOrFail();

        $appointmentOfferDetails->update($data);

        return $appointmentOfferDetails;
    }
    public function accept($OfferDetailID): Order
    {
        return DB::transaction(function () use ($OfferDetailID) {
            $appointmentOfferDetail = AppointmentOfferDetail::findOrfail($OfferDetailID);
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

            $qrCodeData = [
                'hash_id'                   => $order->hash_id,
                'order_id'                  => $order->id,
                'client_name'               => auth()->user()?->name,
                'client_phone'              => auth()->user()?->phone,
                'vendor_name'               => $order->vendor?->name,
                'paid_amount'               => $order->invoice?->paid_amount,
                'remain_amount'             => $order->total - $order->invoice?->paid_amount,
                'title'                     => $appointmentOffer->taxonomy->title,
                'item_price'                => $appointmentOfferDetail->offer_price,
                'item_price_after_discount' => null,
                'item_image'                => null
            ];
            $qrCodeImage = (new QrCodeService())->generate($qrCodeData);

            $appointmentOfferDetail->addMediaFromString($qrCodeImage)->toMediaCollection('appointment_details');

            $appointmentOffer->update([
                'total' => $total
            ]);

            return $order;
        });
    }

    public function reject($OfferDetailID): void
    {
        $appointmentOfferDetail = AppointmentOfferDetail::findOrfail($OfferDetailID);
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

            $appointmentOffer->invoice->update(['status' => 'paid']);
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

    /**
     * Pay Via Wallet
     *
     * @param  mixed $data
     * @return void
     */
    public function payViaWallet(User $user, array $data): void
    {
        $appointmentOffer = AppointmentOffer::findOrFail($data['id']);
        if ($appointmentOffer->status->value != AppointmentOfferEnum::Paid_Application_Fees->value) {
            $wallet = $user->myWallet();
            $appointmentOfferPrice = $appointmentOffer->application_fees;
            if ($wallet->balance >= $appointmentOfferPrice) {
                try {
                    DB::beginTransaction();
                    $transaction = $this->walletService->debit($user, $appointmentOfferPrice);
                    $data['payment_id'] = $transaction->transaction_number;
                    $this->changePaymentStatus($data);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Transaction failed: ' . $e->getMessage());
                    throw $e;
                }
            }else{
                throw ValidationException::withMessages([
                    'message' => __('There is no sufficient balance'),
                ]);
            }
        }
    }
}
