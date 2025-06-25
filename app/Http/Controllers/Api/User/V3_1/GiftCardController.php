<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\GiftCard;
use Illuminate\Http\Request;
use App\Http\Resources\User\V3_1\GiftCardResource;
use App\Http\Requests\V3_1\User\GiftCardRequest;
use App\Models\Media;
use App\Models\User;

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
        $media = null;
        $user = $request->user();
        // User selection/creation logic
        if (!empty($data['create_new_user'])) {
            $giftUser = User::firstOrCreate(
                ['phone' => $data['new_user_phone']],
                [
                    'name' => $data['new_user_name'],
                    'email' => $data['new_user_email'] ?? null,
                    'status' => User::STATUS_ACTIVE,
                ]
            );
            $data['user_id'] = $giftUser->id;
        } elseif (!empty($data['user_id'])) {
            $data['user_id'] = $data['user_id'];
        } else {
            $data['user_id'] = null;
        }
        $data['code'] = strtoupper(uniqid('GC'));
        // If admin, allow specifying created_by, else default to self
        if ($user->hasRole('admin') && !empty($data['created_by'])) {
            $data['created_by'] = $data['created_by'];
        } else {
            $data['created_by'] = $user->id;
        }
        // Set product_type if type is service
        if ($data['type'] === 'service') {
            $data['product_type'] = 'service';
        } elseif ($data['type'] === 'product') {
            $data['product_type'] = 'product';
        }
        // Handle background image association
        if (!empty($data['background_image'])) {
            $mediaId = $data['background_image'];
            $media = Media::find($mediaId);
            if ($media && $media->model_type === 'App\\Models\\TemporaryUpload') {
                $media->model_type = GiftCard::class;
            }
        }
        $giftCard = GiftCard::create($data);
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

    /**
     * Unified endpoint for sent and received gift cards.
     * Query param 'type' can be 'sent', 'received', or 'all' (default: all)
     */
    public function myGiftCards(Request $request)
    {
        $user = $request->user();
        $type = $request->query('type', 'all');
        $query = GiftCard::query();

        if ($type === 'sent') {
            $query->where('created_by', $user->id);
        } elseif ($type === 'received') {
            $query->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('recipient_email', $user->email)
                  ->orWhere('recipient_number', $user->phone);
            });
        } else { // all
            $query->where(function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('user_id', $user->id)
                  ->orWhere('recipient_email', $user->email)
                  ->orWhere('recipient_number', $user->phone);
            });
        }

        $giftCards = $query->latest()->paginate(20);
        return GiftCardResource::collection($giftCards)
            ->additional(['success' => true, 'message' => __('api.success')]);
    }
}
