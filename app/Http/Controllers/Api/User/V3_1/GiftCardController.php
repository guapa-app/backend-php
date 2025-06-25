<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Models\User;
use App\Models\Media;
use App\Models\GiftCard;
use App\Models\GiftCardBackground;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\V3_1\User\GiftCardRequest;
use App\Http\Resources\User\V3_1\GiftCardResource;

class GiftCardController extends BaseApiController
{
    public function index(Request $request)
    {
        $giftCards = GiftCard::where('user_id', $request->user()->id)
            ->with(['order', 'walletTransaction', 'backgroundImage'])
            ->latest()
            ->paginate(20);

        return GiftCardResource::collection($giftCards)
            ->additional(['success' => true, 'message' => __('api.success')]);
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

        // Set created_by
        if ($user->hasRole('admin') && !empty($data['created_by'])) {
            $data['created_by'] = $data['created_by'];
        } else {
            $data['created_by'] = $user->id;
        }

        // Handle background image association
        if (!empty($data['background_image_id'])) {
            // Use admin-uploaded background image
            $background = GiftCardBackground::find($data['background_image_id']);
            if ($background && $background->is_active) {
                $data['background_image_id'] = $background->id;
            }
        } elseif (!empty($data['background_image'])) {
            // Handle temporary upload
            $mediaId = $data['background_image'];
            $media = Media::find($mediaId);
            if ($media && $media->model_type === 'App\\Models\\TemporaryUpload') {
                $media->model_type = GiftCard::class;
            }
        }

        // Create the gift card
        $giftCard = GiftCard::create($data);

        // Associate uploaded media if exists
        if (!empty($media) && $media->model_type === GiftCard::class) {
            $media->model_id = $giftCard->id;
            $media->collection_name = 'gift_card_backgrounds';
            $media->save();
            $giftCard->background_image = $media->getUrl();
            $giftCard->save();
        }

        return GiftCardResource::make($giftCard)
            ->additional(['success' => true, 'message' => __('api.created')]);
    }

    public function show($id)
    {
        $giftCard = GiftCard::where('user_id', auth()->id())
            ->with(['order', 'walletTransaction', 'backgroundImage'])
            ->findOrFail($id);

        return GiftCardResource::make($giftCard)
            ->additional(['success' => true, 'message' => __('api.success')]);
    }

    /**
     * Unified endpoint for sent and received gift cards.
     * Query param 'type' can be 'sent', 'received', or 'all' (default: all)
     */
    public function myGiftCards(Request $request)
    {
        $user = $request->user();
        $type = $request->query('type', 'all');
        $query = GiftCard::with(['order', 'walletTransaction', 'backgroundImage']);

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

    /**
     * Get gift card options including colors and background images
     */
    public function options()
    {
        $options = [
            'gift_type' => [
                'wallet' => 'Wallet Credit',
                'order' => 'Order',
            ],
            'background_colors' => config('gift_card.colors'),
            'background_images' => GiftCardBackground::active()
                ->with('media')
                ->get()
                ->map(function ($background) {
                    return [
                        'id' => $background->id,
                        'name' => $background->name,
                        'description' => $background->description,
                        'image_url' => $background->image_url,
                        'thumbnail_url' => $background->thumbnail_url,
                    ];
                }),
            'suggested_amounts' => config('gift_card.suggested_amounts', [50, 100, 200, 500, 1000]),
            'currencies' => [
                'SAR' => 'Saudi Riyal',
                'USD' => 'US Dollar',
                'EUR' => 'Euro',
            ],
        ];
        return response()->json(['success' => true, 'message' => __('api.success'), 'data' => $options]);
    }

    /**
     * Redeem gift card to wallet
     */
    public function redeemToWallet($id)
    {
        $giftCard = GiftCard::where('user_id', auth()->id())->findOrFail($id);

        if (!$giftCard->canBeRedeemed()) {
            return response()->json([
                'success' => false,
                'message' => __('api.gift_card_cannot_be_redeemed'),
            ], 400);
        }

        if (!$giftCard->isWalletType()) {
            return response()->json([
                'success' => false,
                'message' => __('api.gift_card_not_wallet_type'),
            ], 400);
        }

        if ($giftCard->redeemToWallet()) {
            return response()->json([
                'success' => true,
                'message' => __('api.gift_card_redeemed_to_wallet'),
                'data' => new GiftCardResource($giftCard->fresh(['walletTransaction']))
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __('api.gift_card_redemption_failed'),
        ], 500);
    }

    /**
     * Create order from gift card
     */
    public function createOrder($id)
    {
        $giftCard = GiftCard::where('user_id', auth()->id())->findOrFail($id);

        if (!$giftCard->canBeRedeemed()) {
            return response()->json([
                'success' => false,
                'message' => __('api.gift_card_cannot_be_redeemed'),
            ], 400);
        }

        if (!$giftCard->isOrderType()) {
            return response()->json([
                'success' => false,
                'message' => __('api.gift_card_not_order_type'),
            ], 400);
        }

        $order = $giftCard->createOrder();

        if ($order) {
            return response()->json([
                'success' => true,
                'message' => __('api.order_created_from_gift_card'),
                'data' => [
                    'gift_card' => new GiftCardResource($giftCard->fresh(['order'])),
                    'order' => $order
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __('api.order_creation_failed'),
        ], 500);
    }

    /**
     * Cancel order and redeem gift card to wallet
     */
    public function cancelOrderAndRedeemToWallet($id)
    {
        $giftCard = GiftCard::where('user_id', auth()->id())->findOrFail($id);

        if (!$giftCard->order) {
            return response()->json([
                'success' => false,
                'message' => __('api.no_order_found'),
            ], 400);
        }

        if ($giftCard->redemption_method !== GiftCard::REDEMPTION_ORDER) {
            return response()->json([
                'success' => false,
                'message' => __('api.gift_card_not_redeemed_as_order'),
            ], 400);
        }

        if ($giftCard->cancelOrderAndRedeemToWallet()) {
            return response()->json([
                'success' => true,
                'message' => __('api.order_cancelled_and_redeemed_to_wallet'),
                'data' => new GiftCardResource($giftCard->fresh(['walletTransaction']))
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __('api.cancellation_failed'),
        ], 500);
    }

    /**
     * Get gift card by code
     */
    public function getByCode(Request $request)
    {
        $code = $request->get('code');

        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => __('api.code_required'),
            ], 400);
        }

        $giftCard = GiftCard::where('code', $code)
            ->with(['order', 'walletTransaction', 'backgroundImage'])
            ->first();

        if (!$giftCard) {
            return response()->json([
                'success' => false,
                'message' => __('api.gift_card_not_found'),
            ], 404);
        }

        return GiftCardResource::make($giftCard)
            ->additional(['success' => true, 'message' => __('api.success')]);
    }
}
