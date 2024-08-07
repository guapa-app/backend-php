<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetClientOrdersRequest;
use App\Http\Requests\VendorClientRequest;
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

    public function index(Request $request, Vendor $vendor)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
        ]);
        $filters = $request->only(['name', 'phone']);
        return $this->vendorClientService->listClientsWithOrderCount($vendor, $filters);
    }


    public function store(VendorClientRequest $request, Vendor $vendor)
    {
        return $this->vendorClientService->addClient($vendor, $request->validated());
    }

    public function getClientOrders(GetClientOrdersRequest $request, Vendor $vendor, User $client)
    {
        $productType = $request->input('product_type', null);
        return $this->vendorClientService->getClientOrders($vendor, $client, $productType);

    }
    public function delete(Vendor $vendor, $clientId)
    {
        return $this->vendorClientService->deleteClient($vendor, $clientId);
    }
}
