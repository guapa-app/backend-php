<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Contracts\Repositories\MarketingCampaignRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Vendor\V3_1\MarketingCampaignRequest;
use App\Http\Resources\Vendor\MarketingCampaignCollection;
use App\Http\Resources\Vendor\MarketingCampaignResource;
use App\Models\Setting;
use App\Services\MarketingCampaignService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class MarketingCampaignController extends BaseApiController
{
    protected $marketingCampaignService;
    protected $marketingCampaignRepository;

    public function __construct(MarketingCampaignService $marketingCampaignService, MarketingCampaignRepositoryInterface $marketingCampaignRepository)
    {
        parent::__construct();

        $this->marketingCampaignService = $marketingCampaignService;
        $this->marketingCampaignRepository = $marketingCampaignRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->merge(['vendor_id' =>  $this->user->managerVendorId()]);
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
        try {
            $data = $request->validated();
            $data['vendor_id'] = $this->user->managerVendorId();
            $marketingCampaign = $this->marketingCampaignService->create($data);

            return MarketingCampaignResource::make($marketingCampaign)
                ->additional([
                    'success' => true,
                    'message' => __('api.success'),
                ]);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the marketing campaign',
            ], 500);
        }
    }

    public function availableCustomers()
    {
        $availableCustomers = Setting::getCampaignAvailableCustomers();

        return $this->successJsonRes(['items' => $availableCustomers], __('api.success'));
    }

    public function changeStatus(Request $request)
    {
        $this->marketingCampaignService->changeStatus($request);

        return true;
    }

    /**
     * calculate Campaign Pricing.
     */
    public function calculatePricing(MarketingCampaignRequest $request)
    {
        try {
            $data = $request->validated();
            $data['vendor_id'] = $this->user->managerVendorId();
            $pricing = $this->marketingCampaignService->calculatePricingDetails($data);

            return $this->successJsonRes($pricing, __('api.success'));
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the marketing campaign',
            ], 500);
        }
    }
}
