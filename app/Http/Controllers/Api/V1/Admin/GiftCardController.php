<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\User;
use App\Models\Offer;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\GiftCard;
use Illuminate\Http\Request;
use App\Models\GiftCardBackground;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\V1\Admin\GiftCardRequest;
use App\Http\Resources\Admin\V1\GiftCardResource;

class GiftCardController extends BaseApiController
{
    /**
     * List all gift cards with filtering and pagination
     */
    public function index(Request $request)
    {
        $query = GiftCard::with([
            'user', 'vendor', 'product', 'offer', 'order',
            'walletTransaction', 'backgroundImage', 'createdBy'
        ]);

        // Filter by gift type
        if ($request->has('gift_type')) {
            $query->where('gift_type', $request->gift_type);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by redemption method
        if ($request->has('redemption_method')) {
            $query->where('redemption_method', $request->redemption_method);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by amount range
        if ($request->has('amount_from')) {
            $query->where('amount', '>=', $request->amount_from);
        }
        if ($request->has('amount_to')) {
            $query->where('amount', '<=', $request->amount_to);
        }

        // Search by code, recipient name, or email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%")
                  ->orWhere('recipient_email', 'like', "%{$search}%");
            });
        }

        // Filter by vendor
        if ($request->has('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        // Filter by user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $giftCards = $query->latest()->paginate($request->get('per_page', 20));

        return GiftCardResource::collection($giftCards)
            ->additional(['success' => true, 'message' => __('api.success')]);
    }

    /**
     * Create a new gift card
     */
    public function store(GiftCardRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            // Handle user creation if needed
            if (!empty($data['create_new_user'])) {
                $user = User::create([
                    'name' => $data['new_user_name'],
                    'phone' => $data['new_user_phone'],
                    'email' => $data['new_user_email'] ?? null,
                ]);
                $data['user_id'] = $user->id;
            }

            // Create the gift card
            $giftCard = GiftCard::create($data);

            // Handle custom background image upload
            if ($request->hasFile('background_image')) {
                $media = $giftCard->addMediaFromRequest('background_image')
                    ->toMediaCollection('gift_card_backgrounds');
                $giftCard->background_image = $media->getUrl();
                $giftCard->save();
            }

            DB::commit();

            return GiftCardResource::make($giftCard->load([
                'user', 'vendor', 'product', 'offer', 'backgroundImage'
            ]))->additional(['success' => true, 'message' => __('api.created')]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => __('api.error_occurred'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific gift card
     */
    public function show($id)
    {
        $giftCard = GiftCard::with([
            'user', 'vendor', 'product', 'offer', 'order',
            'walletTransaction', 'backgroundImage', 'createdBy'
        ])->findOrFail($id);

        return GiftCardResource::make($giftCard)
            ->additional(['success' => true, 'message' => __('api.success')]);
    }

    /**
     * Update a gift card
     */
    public function update(GiftCardRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $giftCard = GiftCard::findOrFail($id);
            $data = $request->validated();

            // Handle user creation if needed
            if (!empty($data['create_new_user'])) {
                $user = User::create([
                    'name' => $data['new_user_name'],
                    'phone' => $data['new_user_phone'],
                    'email' => $data['new_user_email'] ?? null,
                ]);
                $data['user_id'] = $user->id;
            }

            $giftCard->update($data);

            // Handle custom background image upload
            if ($request->hasFile('background_image')) {
                // Remove old media
                $giftCard->clearMediaCollection('gift_card_backgrounds');

                // Add new media
                $media = $giftCard->addMediaFromRequest('background_image')
                    ->toMediaCollection('gift_card_backgrounds');
                $giftCard->background_image = $media->getUrl();
                $giftCard->save();
            }

            DB::commit();

            return GiftCardResource::make($giftCard->load([
                'user', 'vendor', 'product', 'offer', 'backgroundImage'
            ]))->additional(['success' => true, 'message' => __('api.updated')]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => __('api.error_occurred'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a gift card
     */
    public function destroy($id)
    {
        try {
            $giftCard = GiftCard::findOrFail($id);

            // Check if gift card can be deleted
            if ($giftCard->status === GiftCard::STATUS_USED) {
                return response()->json([
                    'success' => false,
                    'message' => __('api.gift_card_already_used'),
                ], 400);
            }

            $giftCard->delete();

            return response()->json([
                'success' => true,
                'message' => __('api.deleted'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('api.error_occurred'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get gift card statistics
     */
    public function statistics()
    {
        $stats = [
            'total_gift_cards' => GiftCard::count(),
            'active_gift_cards' => GiftCard::where('status', GiftCard::STATUS_ACTIVE)->count(),
            'used_gift_cards' => GiftCard::where('status', GiftCard::STATUS_USED)->count(),
            'expired_gift_cards' => GiftCard::where('status', GiftCard::STATUS_EXPIRED)->count(),
            'cancelled_gift_cards' => GiftCard::where('status', GiftCard::STATUS_CANCELLED)->count(),

            'wallet_type' => GiftCard::where('gift_type', GiftCard::GIFT_TYPE_WALLET)->count(),
            'order_type' => GiftCard::where('gift_type', GiftCard::GIFT_TYPE_ORDER)->count(),

            'total_amount' => GiftCard::sum('amount'),
            'redeemed_amount' => GiftCard::where('status', GiftCard::STATUS_USED)->sum('amount'),

            'this_month' => GiftCard::whereMonth('created_at', now()->month)->count(),
            'this_year' => GiftCard::whereYear('created_at', now()->year)->count(),
        ];

        return response()->json([
            'success' => true,
            'message' => __('api.success'),
            'data' => $stats
        ]);
    }

    /**
     * Get gift card options for admin
     */
    public function options()
    {
        $options = [
            'users' => User::select('id', 'name', 'email')->get(),
            'vendors' => Vendor::select('id', 'name')->get(),
            'products' => Product::select('id', 'title')->get(),
            'offers' => Offer::select('id', 'title')->get(),
            'backgrounds' => GiftCardBackground::active()->select('id', 'name')->get(),
            'gift_types' => [
                GiftCard::GIFT_TYPE_WALLET => 'Wallet Credit',
                GiftCard::GIFT_TYPE_ORDER => 'Order',
            ],
            'statuses' => [
                GiftCard::STATUS_ACTIVE => 'Active',
                GiftCard::STATUS_USED => 'Used',
                GiftCard::STATUS_EXPIRED => 'Expired',
                GiftCard::STATUS_CANCELLED => 'Cancelled',
            ],
            'redemption_methods' => [
                GiftCard::REDEMPTION_PENDING => 'Pending',
                GiftCard::REDEMPTION_WALLET => 'Wallet',
                GiftCard::REDEMPTION_ORDER => 'Order',
            ],
            'currencies' => \App\Models\GiftCardSetting::getSupportedCurrencies(),
            'background_colors' => \App\Models\GiftCardSetting::getBackgroundColors(),
            'suggested_amounts' => \App\Models\GiftCardSetting::getSuggestedAmounts(),
            'min_amount' => \App\Models\GiftCardSetting::getMinAmount(),
            'max_amount' => \App\Models\GiftCardSetting::getMaxAmount(),
            'default_currency' => \App\Models\GiftCardSetting::getDefaultCurrency(),
        ];

        return response()->json([
            'success' => true,
            'message' => __('api.success'),
            'data' => $options
        ]);
    }

    /**
     * Bulk update gift card status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gift_card_ids' => 'required|array',
            'gift_card_ids.*' => 'exists:gift_cards,id',
            'status' => 'required|in:' . implode(',', [
                GiftCard::STATUS_ACTIVE,
                GiftCard::STATUS_USED,
                GiftCard::STATUS_EXPIRED,
                GiftCard::STATUS_CANCELLED
            ]),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('api.validation_error'),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updated = GiftCard::whereIn('id', $request->gift_card_ids)
                ->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => __('api.bulk_updated'),
                'data' => ['updated_count' => $updated]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('api.error_occurred'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get gift card by code
     */
    public function getByCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('api.validation_error'),
                'errors' => $validator->errors()
            ], 422);
        }

        $giftCard = GiftCard::where('code', $request->code)
            ->with([
                'user', 'vendor', 'product', 'offer', 'order',
                'walletTransaction', 'backgroundImage', 'createdBy'
            ])
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
