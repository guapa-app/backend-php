<?php

namespace App\Services;

use App\Models\MarketingCampaign;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vendor;

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

    public function create(array $data)
    {
        $type = $data['type'];
        $typeId = $data['id'];

        // Map entity type to the corresponding model class
        $modelClass = $this->getModelClass($type);
        // Find the entity by its ID
        $model = $modelClass::findOrFail($typeId);

        $data['campaignable_id'] = $model->id;
        $data['campaignable_type'] = $type;
        $cost = $this->messageCost * $data['audience_count'];
        $data['status'] = 'pending';
        $data['message_cost'] = $this->messageCost;
        $data['taxes'] = $cost * ($this->taxesPercentage / 100);
        $data['total_cost'] = $cost + $data['taxes'];

        // Create the marketing campaign
        $campaign = MarketingCampaign::create($data);

        // Attach users to the campaign
        if ($data['audience_type'] === 'vendor_customers') {
            $userIds = $data['users'] ?? [];
        } elseif ($data['audience_type'] === 'guapa_customers') {
            $userIds = User::inRandomOrder()->limit($data['audience_count'])->pluck('id')->toArray();
        }

        if (!empty($userIds)) {
            $campaign->users()->attach($userIds);
        }


        $description = "Marketing Campaign: {$campaign->channel} - #{$campaign->id}";
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
}
