<?php

namespace App\Http\Controllers\Api\V3;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\MarketingCampaignRequest;
use App\Http\Requests\UpdateMarketingCampaignRequest;
use App\Http\Resources\MarketingCampaignResource;
use App\Models\MarketingCampaign;
use App\Models\Setting;
use App\Services\MarketingCampaignService;

class MarketingCampaignController extends BaseApiController
{

    protected $marketingCampaignService;

    public function __construct(MarketingCampaignService $marketingCampaignService)
    {
        $this->marketingCampaignService = $marketingCampaignService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

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

//    availableCustomers
    public function availableCustomers()
    {
        $availableCustomers = Setting::getCampaignAvailableCustomers();
        return  $this->successJsonRes(['available_customers' => $availableCustomers], __('api.success'));

    }





}
