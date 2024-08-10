<?php

namespace App\Http\Controllers\Api\V3;

use App\Http\Controllers\Api\VendorClientController as ApiVendorClientController;
use App\Http\Requests\GetClientOrdersRequest;
use App\Http\Requests\VendorClientRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\V3\VendorClientCollection;
use App\Models\Vendor;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Vendor Clients
 */
class VendorClientController extends ApiVendorClientController
{
    /**
     * Get vendor clients
     *
     * @param Request $request
     * @param Vendor $vendor
     * @return VendorClientCollection
     */
    public function index(Request $request, Vendor $vendor) : VendorClientCollection
    {
        $clients = parent::index($request, $vendor);
        return VendorClientCollection::make($clients)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    /**
     * Add new client to vendor
     *
     * @param VendorClientRequest $request
     * @param Vendor $vendor
     * @return JsonResponse
     */
    public function store(VendorClientRequest $request, Vendor $vendor) : JsonResponse
    {
        try {
            return DB::transaction(function () use ($request, $vendor) {
                $result = parent::store($request, $vendor);

                return $this->successJsonRes($result, __('api.created'));
            });
        } catch (Exception $exception) {
            $this->logReq($exception->getMessage());

            return $this->errorJsonRes(message: __('api.error_occurred'));
        }
    }
    /**
     * Get client orders for a vendor.
     *
     * @group VendorClient
     * @urlParam vendor int required The ID of the vendor. Example: 1
     * @urlParam client int required The ID of the client. Example: 1
     * @queryParam product_type string The product type to filter by (product or service). Example: product
     * @responseFile responses/getClientOrders.json
     *
     * @param GetClientOrdersRequest $request
     * @param  $vendor_id
     * @param  $client_id
     * @return OrderCollection
     */
    public function getClientOrders(GetClientOrdersRequest $request,$vendor_id,$client_id) : OrderCollection
    {
        $orders = $this->vendorClientService->getClientOrders($vendor_id, $client_id);
        return OrderCollection::make($orders)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function destroy(Vendor $vendor, $clientId)
    {


        try {
            parent::destroy($vendor, $clientId);

            return $this->successJsonRes(message: __('api.deleted'));
        } catch (Exception $exception) {
            $this->logReq($exception->getMessage());

            return $this->errorJsonRes(message: __('api.error_occurred'));
        }
    }
}
