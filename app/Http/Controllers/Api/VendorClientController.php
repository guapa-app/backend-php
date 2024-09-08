<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetClientOrdersRequest;
use App\Http\Requests\VendorClientRequest;
use App\Models\User;
use App\Models\Vendor;
use App\Services\VendorClientService;
use Illuminate\Http\Request;

class VendorClientController extends Controller
{
    protected $vendorClientService;

    public function __construct(VendorClientService $vendorClientService)
    {
        $this->vendorClientService = $vendorClientService;
    }

    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
        ]);
        $filters = $request->only(['search']);

        return $this->vendorClientService->listClientsWithOrderCount($filters);
    }

    public function store(VendorClientRequest $request)
    {
        return $this->vendorClientService->addClient($request->validated());
    }

    public function getClientOrders(GetClientOrdersRequest $request,User $client)
    {
        $productType = $request->input('product_type', null);

        return $this->vendorClientService->getClientOrders( $client, $productType);
    }

    public function destroy($clientId)
    {
        return $this->vendorClientService->deleteClient($clientId);
    }
}
