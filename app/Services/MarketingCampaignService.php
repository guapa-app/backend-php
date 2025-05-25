<?php

namespace App\Services;

use App\Services\NotificationInterceptor;

use App\Models\User;
use App\Models\Offer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\MarketingCampaign;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Enums\MarketingCampaignStatus;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\CampaignNotification;
use App\Enums\MarketingCampaignAudienceType;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

class MarketingCampaignService
{
    protected $messageCost;
    protected $taxesPercentage;
    protected $paymentService;
    protected $walletService;

    public function __construct(PaymentService $paymentService, WalletService $walletService)
    {
        $this->paymentService = $paymentService;
        $this->messageCost = Setting::getMessageCost();
        $this->taxesPercentage = Setting::getTaxes();
        $this->walletService = $walletService;
    }

    public function calculatePricingDetails(array $data)
    {
        $type = $data['type'];
        $typeId = $data['id'];
        // Map entity type to the corresponding model class
        $modelClass = $this->getModelClass($type);
        // Find the entity by its ID
        $model = $modelClass::findOrFail($typeId);
        // Check if the user has permission to create a campaign for this entity
        if (!$this->checkModelOwnership($model, $data['vendor_id'])) {
            throw new AuthorizationException(__('You do not have permission to create a campaign for this product.'));
        }

        $audienceCount = $data['audience_type'] === MarketingCampaignAudienceType::VENDOR_CUSTOMERS->value && empty($data['users'])
            ? $this->getVendorClientsCount($type, $model)
            : $data['audience_count'];

        $cost = $this->messageCost * $audienceCount;
        $taxes = $cost * ($this->taxesPercentage / 100);
        $totalCost = $cost + $taxes;

        return [
            'message_cost' => (string) $this->messageCost,
            'audience_count' => (string) $audienceCount,
            'cost' => (string) $cost,
            'taxes' => number_format($taxes, 2),
            'total_cost' => (string) $totalCost,
        ];
    }

    public function create(array $data)
    {
        $type = $data['type'];
        $typeId = $data['id'];

        // Map entity type to the corresponding model class
        $modelClass = $this->getModelClass($type);
        // Find the entity by its ID
        $model = $modelClass::findOrFail($typeId);
        // Check if the user has permission to create a campaign for this entity
        if (!$this->checkModelOwnership($model, $data['vendor_id'])) {
            throw new AuthorizationException(__('You do not have permission to create a campaign for this product.'));
        }
        // Calculate the audience count
        $audienceCount = $data['audience_type'] === MarketingCampaignAudienceType::VENDOR_CUSTOMERS->value && empty($data['users'])
            ? $this->getVendorClientsCount($type, $model)
            : $data['audience_count'];

        // Set campaignable ID and type
        $data['campaignable_id'] = $model->id;
        $data['campaignable_type'] = $type;

        // Calculate costs
        $cost = $this->messageCost * $audienceCount;
        $data['status'] = MarketingCampaignStatus::PENDING;
        $data['message_cost'] = $this->messageCost;
        $data['taxes'] = $cost * ($this->taxesPercentage / 100);
        $data['total_cost'] = $cost + $data['taxes'];

        // Create the marketing campaign
        $campaign = MarketingCampaign::create($data);

        // Attach users to the campaign
        if ($data['audience_type'] === MarketingCampaignAudienceType::VENDOR_CUSTOMERS->value) {
            $userIds = $data['users'] ?? $campaign->vendor->clients->pluck('user_id')->toArray();
        } elseif ($data['audience_type'] === MarketingCampaignAudienceType::GUAPA_CUSTOMERS->value) {
            $userIds = User::inRandomOrder()->limit($data['audience_count'])->pluck('id')->toArray();
        }

        if (!empty($userIds)) {
            $campaign->users()->attach($userIds);
        }

        $description = "Marketing Campaign: #{$campaign->id}";
        $invoice = $this->paymentService->generateInvoice(
            $campaign,
            $description,
            $cost,
            $campaign->taxes
        );
        // Update campaign with invoice URL
        $campaign->invoice_url = $invoice->url;
        $campaign->save();

        return $campaign;
    }

    // TODO - will be deprecated on v3.1 release
    public function changeStatus(Request $request)
    {
        $invoice = Invoice::query()
            ->where('invoice_id', $request->session_id ?? $request->id)
            ->firstOrFail();

        $invoice->updateOrFail(['status' => $request->state ?? $request->status]);

        if ($invoice->status == 'paid') {
            $campaign = $invoice->marketing_campaign;
            // Update campaign status to completed
            $campaign->updateOrFail(['status' => MarketingCampaignStatus::COMPLETED]);

            // handle sending  camping  messages to users here
            $this->sendCampaignMessages($campaign);
        }

        logger(
            "Change Invoice Status By Callback URL\n
            order { $invoice->order_id } <-> Invoice { $invoice->id }",
            [
                "\n***payment gateway***" => $request->all(),
                "\n***invoice***" => $invoice->attributesToArray(),
            ]
        );

        return true;
    }

    public function changePaymentStatus(array $data) : void
    {
        $campaign = MarketingCampaign::findOrfail($data['id']);
        if ($data['status'] == 'paid') {
            $campaign->status =  MarketingCampaignStatus::COMPLETED;
            $campaign->payment_id = $data['payment_id'];
            $campaign->payment_gateway = $data['payment_gateway'];
            $campaign->save();

            // Update invoice status
            $campaign->invoice->update(['status' => 'paid']);
            // Send Campaign Messages
            $this->sendCampaignMessages($campaign);
        } else {
            $campaign->status = MarketingCampaignStatus::FAILED;
            $campaign->save();
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
        $campaign = MarketingCampaign::findOrFail($data['id']);
        if ($campaign->status->value != MarketingCampaignStatus::COMPLETED->value) {
            $wallet = $user->myWallet();
            $campaignPrice = $campaign->total_cost;
            if ($wallet->balance >= $campaignPrice) {
                try {
                    DB::beginTransaction();
                    $transaction = $this->walletService->debit($user, $campaignPrice);
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

    protected function sendCampaignMessages(MarketingCampaign $campaign)
    {
        $notification = new CampaignNotification($campaign);
        // Get the users associated with the campaign
        $users = $campaign->users;
        // Send the notification to all users
        app(\App\Services\NotificationInterceptor::class)->interceptBulk($$users, $$notification);
    }

    /**
     * Get the corresponding model class for a given entity type.
     *
     * @param string $entityType
     * @return string|null
     */
    private function getModelClass($type)
    {
        $modelClasses = [
            'offer' => Offer::class,
            'product' => Product::class,
        ];

        if (!isset($modelClasses[$type])) {
            abort(400, 'Invalid type');
        }

        return $modelClasses[$type];
    }

    private function getVendorClientsCount($type, $model)
    {
        return $type === 'offer'
            ? $model->product->vendor->clients->count()
            : $model->vendor->clients->count();
    }

    private function checkModelOwnership(Model $model, int $vendorId): bool
    {
        if ($model instanceof Offer) {
            return $model->product->vendor_id === $vendorId;
        } else {
            return $model->vendor_id === $vendorId;
        }
    }
}
