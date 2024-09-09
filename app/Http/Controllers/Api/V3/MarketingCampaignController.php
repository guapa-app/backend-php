<?php

namespace App\Http\Controllers\Api\V3;

use App\Contracts\Repositories\MarketingCampaignRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\MarketingCampaignRequest;
use App\Http\Resources\V3\MarketingCampaignCollection;
use App\Http\Resources\V3\MarketingCampaignResource;
use App\Models\Setting;
use App\Services\MarketingCampaignService;
use Illuminate\Http\Request;

class MarketingCampaignController extends BaseApiController
{
    protected $marketingCampaignService;
    protected $marketingCampaignRepository;
    public function __construct(MarketingCampaignService $marketingCampaignService, MarketingCampaignRepositoryInterface $marketingCampaignRepository)
    {
        $this->marketingCampaignService = $marketingCampaignService;
        $this->marketingCampaignRepository = $marketingCampaignRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $marketingCampaigns = $this->marketingCampaignRepository->all($request);
        return MarketingCampaignCollection::make($marketingCampaigns)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(MarketingCampaignRequest $request)
    {
        $data = $request->validated();
        $marketingCampaign = $this->marketingCampaignService->create($data);

        return MarketingCampaignResource::make($marketingCampaign)
        ->additional([
            'success' => true,
            'message' => __('api.success'),
        ]);
    }
    public function availableCustomers()
    {
        $availableCustomers = Setting::getCampaignAvailableCustomers();
        return  $this->successJsonRes(['items' => $availableCustomers], __('api.success'));
    }
    public function changeStatus(Request $request)
    {
        $this->marketingCampaignService->changeStatus($request);
        return true;
    }
    /**
     * calculate Campaign Pricing
     */
    public function calculatePricing(MarketingCampaignRequest $request)
    {
        $data = $request->all();
        $pricing = $this->marketingCampaignService->calculatePricingDetails($data);
        return $this->successJsonRes($pricing, __('api.success'));
    }
}
