<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\GiftCard;
use Illuminate\Http\Request;
use App\Http\Resources\User\V3_1\GiftCardResource;
use App\Http\Requests\V3_1\User\GiftCardRequest;
use App\Models\Media;

class GiftCardController extends BaseApiController
{
    public function index(Request $request)
    {
        $giftCards = GiftCard::where('user_id', $request->user()->id)->latest()->paginate(20);
        return GiftCardResource::collection($giftCards)->additional(['success' => true, 'message' => __('api.success')]);
    }

    public function store(GiftCardRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['code'] = strtoupper(uniqid('GC'));

        // Handle background image association
        if (!empty($data['background_image'])) {
            $mediaId = $data['background_image'];
            $media = Media::find($mediaId);
            if ($media && $media->model_type === 'App\\Models\\TemporaryUpload') {
                $media->model_type = GiftCard::class;
                // We'll set model_id after creation
            }
        }

        $giftCard = GiftCard::create($data);

        // Move media to gift card collection if needed
        if (!empty($media) && $media->model_type === GiftCard::class) {
            $media->model_id = $giftCard->id;
            $media->collection_name = 'gift_card_backgrounds';
            $media->save();
            $giftCard->background_image = $media->getUrl();
            $giftCard->save();
        }

        return GiftCardResource::make($giftCard)->additional(['success' => true, 'message' => __('api.created')]);
    }

    public function show($id)
    {
        $giftCard = GiftCard::where('user_id', auth()->id())->findOrFail($id);
        return GiftCardResource::make($giftCard)->additional(['success' => true, 'message' => __('api.success')]);
    }
}
