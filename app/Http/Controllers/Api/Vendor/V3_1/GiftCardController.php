<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\GiftCard;
use Illuminate\Http\Request;
use App\Http\Resources\Vendor\V3_1\GiftCardResource;

class GiftCardController extends BaseApiController
{
    public function index(Request $request)
    {
        $vendorId = $request->user()->vendor_id;
        $giftCards = GiftCard::where('vendor_id', $vendorId)->latest()->paginate(20);
        return GiftCardResource::collection($giftCards)->additional(['success' => true, 'message' => __('api.success')]);
    }

    public function show($id)
    {
        $vendorId = auth()->user()->vendor_id;
        $giftCard = GiftCard::where('vendor_id', $vendorId)->findOrFail($id);
        return GiftCardResource::make($giftCard)->additional(['success' => true, 'message' => __('api.success')]);
    }
}
