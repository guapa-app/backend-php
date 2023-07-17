<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\VendorController as ApiVendorController;
use App\Http\Requests\VendorRequest;
use App\Http\Resources\VendorCollection;
use App\Http\Resources\VendorResource;
use Illuminate;
use Illuminate\Http\Request;

/**
 * @group Vendors
 */
class VendorController extends ApiVendorController
{
    public function index(Request $request)
    {
        $index = parent::index($request);

        return VendorCollection::make($index)
            ->additional([
                "success" => true,
                'message' => __('api.success'),
            ]);
    }

    public function single(Request $request, $id)
    {
        $item = parent::single($request, $id);

        return VendorResource::make($item)
            ->additional([
                "success" => true,
                'message' => __('api.success'),
            ]);
    }

    public function create(VendorRequest $request)
    {
        $item = parent::create($request);

        return VendorResource::make($item)
            ->additional([
                "success" => true,
                'message' => __('api.created'),
            ]);
    }

    public function update(VendorRequest $request, $id)
    {
        $item = parent::update($request, $id);

        return VendorResource::make($item)
            ->additional([
                "success" => true,
                'message' => __('api.updated'),
            ]);
    }

    public function share(Request $request, $id)
    {
        $sharesCount = parent::share($request, $id);

        return $this->successJsonRes($sharesCount, __('api.success'));
    }
}
