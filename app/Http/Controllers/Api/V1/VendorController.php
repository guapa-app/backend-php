<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\VendorController as ApiVendorController;
use App\Http\Requests\VendorRequest;
use Illuminate\Http\Request;

class VendorController extends ApiVendorController
{
    public function index(Request $request)
    {
        return response()->json(parent::index($request));
    }

    public function single(Request $request, $id)
    {
        return response()->json(parent::single($request, $id));
    }

    public function create(VendorRequest $request)
    {
        return response()->json(parent::create($request));
    }

    public function update(VendorRequest $request, $id)
    {
        return response()->json(parent::update($request, $id));
    }

    public function share(Request $request, $id)
    {
        return response()->json(parent::share($request, $id));
    }
}
