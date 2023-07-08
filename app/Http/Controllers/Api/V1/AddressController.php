<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\AddressController as ApiAddressController;
use App\Http\Requests\AddressListRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Http\Request;

class AddressController extends ApiAddressController
{
    public function index(AddressListRequest $request)
    {
        return response()->json(parent::index($request));
    }

    public function single(Request $request)
    {
        return response()->json(parent::single($request));
    }

    public function create(AddressRequest $request)
    {
        return response()->json(parent::create($request));
    }

    public function update(AddressRequest $request, $id = 0)
    {
        return response()->json(parent::update($request, $id));
    }

    public function delete(int $id = 0)
    {
        return response()->json(parent::delete($id));
    }
}
