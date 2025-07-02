<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Models\User;
use App\Models\Media;
use App\Models\GiftCard;
use Illuminate\Http\Request;
use App\Models\GiftCardBackground;
use App\Services\GiftCardService;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\V3_1\User\GiftCardRequest;
use App\Http\Resources\User\V3_1\GiftCardResource;

class GiftCardController extends BaseApiController
{
    protected $giftCardService;

    public function __construct(GiftCardService $giftCardService)
    {
        parent::__construct();
        $this->giftCardService = $giftCardService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $giftCards = GiftCard::where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->orWhere('recipient_id', $user->id)
                ->orWhere('user_id', $user->id);
        })
            ->with(['order', 'walletTransaction', 'backgroundImage'])
            ->latest()
            ->paginate(20);

        return GiftCardResource::collection($giftCards)
            ->additional(['success' => true, 'message' => __('api.success')]);
    }

    public function store(GiftCardRequest $request)
    {
        try {
            $data = $request->validated();
            $user = $request->user();

            // Handle user selection/creation logic
            $data = $this->handleUserSelection($data, $user);

            // Create gift card using service
            $giftCard = $this->giftCardService->createGiftCard($data, $user);

            return GiftCardResource::make($giftCard)
                ->additional(['success' => true, 'message' => __('api.created')]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Handle user selection and creation logic
     */
    private function handleUserSelection($data, $user)
    {
        // User selection/creation logic - ensure we always have a valid user_id
        if (!empty($data['create_new_user'])) {
            // Create a new user for the gift card
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
            // Use existing user ID
            $existingUser = User::find($data['user_id']);
            if (!$existingUser) {
                throw new \Exception('Selected user not found. Please provide a valid user ID or create a new user.');
            }
            $data['user_id'] = $existingUser->id;
        } elseif (!empty($data['recipient_email'])) {
            // Try to find user by email
            $existingUser = User::where('email', $data['recipient_email'])->first();
            if ($existingUser) {
                $data['user_id'] = $existingUser->id;
            } else {
                // Generate a unique placeholder phone if not provided
                $phone = $data['recipient_number'] ?? null;
                if (empty($phone)) {
                    do {
                        $phone = '000' . rand(1000000, 9999999);
                    } while (User::where('phone', $phone)->exists());
                }
                // Create user from email if not found
                $giftUser = User::create([
                    'name' => $data['recipient_name'],
                    'email' => $data['recipient_email'],
                    'phone' => $phone,
                    'status' => User::STATUS_ACTIVE,
                ]);
                $data['user_id'] = $giftUser->id;
            }
        } elseif (!empty($data['recipient_number'])) {
            // Try to find user by phone
            $existingUser = User::where('phone', $data['recipient_number'])->first();
            if ($existingUser) {
                $data['user_id'] = $existingUser->id;
            } else {
                // Generate a unique placeholder phone if not provided
                $phone = $data['recipient_number'] ?? null;
                if (empty($phone)) {
                    do {
                        $phone = '000' . rand(1000000, 9999999);
                    } while (User::where('phone', $phone)->exists());
                }
                // Create user from phone if not found
                $giftUser = User::create([
                    'name' => $data['recipient_name'],
                    'email' => $data['recipient_email'] ?? null,
                    'phone' => $phone,
                    'status' => User::STATUS_ACTIVE,
                ]);
                $data['user_id'] = $giftUser->id;
            }
        } else {
            // If no user identification provided, return error
            throw new \Exception('Please provide either a user ID, recipient email, recipient phone number, or create a new user.');
        }

        // Set sender_id
        if ($user->hasRole('admin') && !empty($data['sender_id'])) {
            $sender = User::find($data['sender_id']);
            if (!$sender) {
                throw new \Exception('Sender user not found.');
            }
            $data['sender_id'] = $sender->id;
        } else {
            $data['sender_id'] = $user->id;
        }

        return $data;
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $giftCard = GiftCard::where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->orWhere('recipient_id', $user->id)
                ->orWhere('user_id', $user->id);
        })
            ->with(['order', 'walletTransaction', 'backgroundImage'])
            ->find($id);

        if (!$giftCard) {
            return response()->json([
                'success' => false,
                'message' => __('api.gift_card_not_found'),
            ], 404);
        }

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
            $query->where('sender_id', $user->id);
        } elseif ($type === 'received') {
            $query->where(function ($q) use ($user) {
                $q->where('recipient_id', $user->id)
                    ->orWhere('user_id', $user->id);

                // Only add email condition if user has email
                if (!empty($user->email)) {
                    $q->orWhere('recipient_email', $user->email);
                }

                // Only add phone condition if user has phone
                if (!empty($user->phone)) {
                    $q->orWhere('recipient_number', $user->phone);
                }
            });
        } else { // all
            $query->where(function ($q) use ($user) {
                $q->where('sender_id', $user->id)
                    ->orWhere('recipient_id', $user->id)
                    ->orWhere('user_id', $user->id);

                // Only add email condition if user has email
                if (!empty($user->email)) {
                    $q->orWhere('recipient_email', $user->email);
                }

                // Only add phone condition if user has phone
                if (!empty($user->phone)) {
                    $q->orWhere('recipient_number', $user->phone);
                }
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
            'background_colors' => \App\Models\GiftCardSetting::getBackgroundColors(),
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
            'suggested_amounts' => \App\Models\GiftCardSetting::getSuggestedAmounts(),
            'currencies' => \App\Models\GiftCardSetting::getSupportedCurrencies(),
            'min_amount' => \App\Models\GiftCardSetting::getMinAmount(),
            'max_amount' => \App\Models\GiftCardSetting::getMaxAmount(),
            'default_currency' => \App\Models\GiftCardSetting::getDefaultCurrency(),
        ];
        return response()->json(['success' => true, 'message' => __('api.success'), 'data' => $options]);
    }

    /**
     * Redeem gift card to wallet
     */
    public function redeemToWallet(Request $request, $id)
    {
        $user = $request->user();
        $giftCard = GiftCard::where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->orWhere('recipient_id', $user->id)
                ->orWhere('user_id', $user->id);
        })->find($id);

        if (!$giftCard) {
            return response()->json([
                'success' => false,
                'message' => __('api.gift_card_not_found'),
            ], 404);
        }

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
    public function createOrder(Request $request, $id)
    {
        $user = $request->user();
        $giftCard = GiftCard::where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->orWhere('recipient_id', $user->id)
                ->orWhere('user_id', $user->id);
        })->find($id);

        if (!$giftCard) {
            return response()->json([
                'success' => false,
                'message' => __('api.gift_card_not_found'),
            ], 404);
        }

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
                    'order' => $order,
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
    public function cancelOrderAndRedeemToWallet(Request $request, $id)
    {
        $user = $request->user();
        $giftCard = GiftCard::where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->orWhere('recipient_id', $user->id)
                ->orWhere('user_id', $user->id);
        })->find($id);

        if (!$giftCard) {
            return response()->json([
                'success' => false,
                'message' => __('api.gift_card_not_found'),
            ], 404);
        }

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
