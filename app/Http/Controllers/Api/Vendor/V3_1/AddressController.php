<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Http\Controllers\Api\AddressController as ApiAddressController;
use App\Http\Requests\AddressListRequest;
use App\Http\Requests\AddressRequest;
use App\Http\Resources\AddressCollection;
use App\Http\Resources\AddressResource;
use Illuminate\Http\Request;

/**
 * @group Addresses
 */
class AddressController extends ApiAddressController
{
    public function index(AddressListRequest $request)
    {
        $index = parent::index($request);

        return AddressCollection::make($index)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function single(Request $request)
    {
        $single = parent::single($request);

        return AddressResource::make($single)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function create(AddressRequest $request)
    {
        $item = parent::create($request);

        return AddressResource::make($item)
            ->additional([
                'success' => true,
                'message' => __('api.created'),
            ]);
    }

    public function update(AddressRequest $request, $id = 0)
    {
        $item = parent::update($request, $id);

        return AddressResource::make($item)
            ->additional([
                'success' => true,
                'message' => __('api.updated'),
            ]);
    }

    public function delete(int $id = 0)
    {
        parent::delete($id);

        return $this->successJsonRes([], __('api.deleted'));
    }
}
