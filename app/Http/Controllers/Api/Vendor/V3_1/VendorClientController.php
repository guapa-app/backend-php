<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\GetClientOrdersRequest;
use App\Http\Requests\VendorClientRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\V3\VendorClientCollection;
use App\Models\Vendor;
use App\Services\VendorClientService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Vendor Clients
 */
class VendorClientController extends BaseApiController
{
    protected $vendorClientService;

    public function __construct(VendorClientService $vendorClientService)
    {
        parent::__construct();
        $this->vendorClientService = $vendorClientService;
    }

    /**
     * Get vendor clients.
     *
     * @param Request $request
     * @return VendorClientCollection
     */
    public function index(Request $request): VendorClientCollection
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
        ]);
        $filters = $request->only(['search']);
        $vendor = $this->getVendor();

        $clients = $this->vendorClientService->listClientsWithOrderCount($vendor, $filters);

        return VendorClientCollection::make($clients)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    /**
     * Add new client to vendor.
     *
     * @param VendorClientRequest $request
     * @return JsonResponse
     */
    public function store(VendorClientRequest $request): JsonResponse
    {
        try {
            $vendor = $this->getVendor();
            $result = $this->vendorClientService->addClient($vendor, $request->validated());

            return $this->successJsonRes($result, __('api.created'));
        } catch (Exception $exception) {
            $this->logReq($exception->getMessage());

            return $this->errorJsonRes(message: __('api.error_occurred'));
        }
    }

    /**
     * Get client orders for a vendor.
     *
     * @group VendorClient
     * @urlParam client int required The ID of the client. Example: 1
     * @queryParam product_type string The product type to filter by (product or service). Example: product
     * @responseFile responses/getClientOrders.json
     *
     * @param GetClientOrdersRequest $request
     * @param int $client_id
     * @return OrderCollection
     */
    public function getClientOrders(GetClientOrdersRequest $request, $client_id): OrderCollection
    {
        $vendor = $this->getVendor();
        $productType = $request->input('product_type');

        $orders = $this->vendorClientService->getClientOrders($vendor->id, $client_id, $productType);

        return OrderCollection::make($orders)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    /**
     * Delete a client from vendor.
     *
     * @param int $clientId
     * @return JsonResponse
     */
    public function destroy($clientId): JsonResponse
    {
        try {
            $vendor = $this->getVendor();
            $result = $this->vendorClientService->deleteClient($vendor, $clientId);

            if ($result) {
                return $this->successJsonRes(message: __('api.deleted'));
            } else {
                return $this->errorJsonRes(message: __('api.not_found'));
            }
        } catch (Exception $exception) {
            $this->logReq($exception->getMessage());

            return $this->errorJsonRes(message: __('api.error_occurred'));
        }
    }
    private function getVendor(): Vendor
    {
        return auth()->user()->userVendor?->vendor;
    }
}
