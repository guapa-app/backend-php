<?php

namespace App\Services;

use App\Enums\MarketingCampaignAudienceType;
use App\Enums\MarketingCampaignStatus;
use App\Models\Invoice;
use App\Models\MarketingCampaign;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\CampaignNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class MarketingCampaignService
{
    protected $messageCost;
    protected $taxesPercentage;
    protected $paymentService;
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
        $this->messageCost = Setting::getMessageCost();
        $this->taxesPercentage = Setting::getTaxes();
    }
    public function calculatePricingDetails(array $data)
    {
        $type = $data['type'];
        $typeId = $data['id'];
        // Map entity type to the corresponding model class
        $modelClass = $this->getModelClass($type);
        // Find the entity by its ID
        $model = $modelClass::findOrFail($typeId);

        $audienceCount = $data['audience_type'] === MarketingCampaignAudienceType::VENDOR_CUSTOMERS->value && empty($data['users'])
            ? $this->getVendorClientsCount($type, $model)
            : $data['audience_count'];

        $cost = $this->messageCost * $audienceCount;
        $taxes = $cost * ($this->taxesPercentage / 100);
        $totalCost = $cost + $taxes;

        return [
            'message_cost' => (string)$this->messageCost,
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

        // Calculate the audience count
        $audienceCount = $data['audience_type'] === MarketingCampaignAudienceType::VENDOR_CUSTOMERS->value && empty($data['users'])
            ?$this->getVendorClientsCount($type, $model)
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

    protected function sendCampaignMessages(MarketingCampaign $campaign)
    {
        $notification = new CampaignNotification($campaign);
        // Get the users associated with the campaign
        $users = $campaign->users;
        // Send the notification to all users
        Notification::send($users, $notification);
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
}
