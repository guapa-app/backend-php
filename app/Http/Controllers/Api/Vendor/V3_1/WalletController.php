<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use Illuminate\Http\Request;
use App\Http\Resources\Vendor\V3_1\WalletResource;
use App\Http\Controllers\Api\BaseApiController;

class WalletController extends BaseApiController
{
    public function index(Request $request)
    {
        $vendor  = $request->user()->vendor;

        return WalletResource::make($vendor)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
