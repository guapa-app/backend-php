<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class GiftCard extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'code',
        'user_id',
        'vendor_id',
        'gift_type', // wallet or order
        'product_id',
        'offer_id',
        'order_id',
        'wallet_transaction_id',
        'amount',
        'currency',
        'background_color',
        'background_image',
        'background_image_id',
        'message',
        'notes',
        'status',
        'redemption_method',
        'expires_at',
        'redeemed_at',
        'recipient_name',
        'recipient_email',
        'recipient_number',
        'sender_id',
        'recipient_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expires_at' => 'datetime',
        'redeemed_at' => 'datetime',
    ];

    // Status constants
    public const STATUS_ACTIVE = 'active';
    public const STATUS_USED = 'used';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_CANCELLED = 'cancelled';

    // Gift type constants
    public const GIFT_TYPE_WALLET = 'wallet';
    public const GIFT_TYPE_ORDER = 'order';

    // Redemption method constants
    public const REDEMPTION_PENDING = 'pending';
    public const REDEMPTION_WALLET = 'wallet';
    public const REDEMPTION_ORDER = 'order';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function walletTransaction()
    {
        return $this->belongsTo(Transaction::class, 'wallet_transaction_id');
    }

    public function backgroundImage()
    {
        return $this->belongsTo(GiftCardBackground::class, 'background_image_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    // Register media collections for background image
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gift_card_backgrounds')->singleFile();
    }

    protected static function booted()
    {
        static::saving(function ($giftCard) {
            // Generate unique code if not set
            if (empty($giftCard->code)) {
                $prefix = \App\Models\GiftCardSetting::getCodePrefix();
                $giftCard->code = strtoupper(uniqid($prefix));
            }

            // Set default status if not set
            if (empty($giftCard->status)) {
                $giftCard->status = self::STATUS_ACTIVE;
            }

            // Set default redemption method if not set
            if (empty($giftCard->redemption_method)) {
                $giftCard->redemption_method = self::REDEMPTION_PENDING;
            }

            // Set default expiry date if not provided
            if (empty($giftCard->expires_at)) {
                $defaultExpirationDays = \App\Models\GiftCardSetting::getDefaultExpirationDays();
                $giftCard->expires_at = now()->addDays($defaultExpirationDays);
            }

            // Validate amount against settings
            $minAmount = \App\Models\GiftCardSetting::getMinAmount();
            $maxAmount = \App\Models\GiftCardSetting::getMaxAmount();

            if ($giftCard->amount < $minAmount) {
                throw new \InvalidArgumentException("Amount cannot be less than {$minAmount}");
            }

            if ($giftCard->amount > $maxAmount) {
                throw new \InvalidArgumentException("Amount cannot exceed {$maxAmount}");
            }

            // Clear conflicting fields based on gift type
            if ($giftCard->gift_type === self::GIFT_TYPE_WALLET) {
                $giftCard->product_id = null;
                $giftCard->offer_id = null;
                $giftCard->vendor_id = null;
            } elseif ($giftCard->gift_type === self::GIFT_TYPE_ORDER) {
                // For order type, ensure we have either product_id or offer_id
                if ($giftCard->product_id) {
                    $giftCard->offer_id = null;
                } elseif ($giftCard->offer_id) {
                    $giftCard->product_id = null;
                }
            }
        });
    }

    // Scopes
    public function scopeWalletType($query)
    {
        return $query->where('gift_type', self::GIFT_TYPE_WALLET);
    }

    public function scopeOrderType($query)
    {
        return $query->where('gift_type', self::GIFT_TYPE_ORDER);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopePendingRedemption($query)
    {
        return $query->where('redemption_method', self::REDEMPTION_PENDING);
    }

    public function scopeSentBy($query, $userId)
    {
        return $query->where('sender_id', $userId);
    }

    public function scopeReceivedBy($query, $user)
    {
        return $query->where(function($q) use ($user) {
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
    }

    // Accessors for display fields
    public function getDisplayNameAttribute()
    {
        return $this->user ? $this->user->name : $this->recipient_name;
    }

    public function getDisplayEmailAttribute()
    {
        return $this->user ? $this->user->email : $this->recipient_email;
    }

    public function getDisplayPhoneAttribute()
    {
        return $this->user ? $this->user->phone : $this->recipient_number;
    }

    public function getBackgroundImageUrlAttribute()
    {
        if ($this->background_image_id && $this->backgroundImage) {
            return $this->backgroundImage->image_url;
        }
        return $this->background_image;
    }

    public function getBackgroundThumbnailUrlAttribute()
    {
        if ($this->background_image_id && $this->backgroundImage) {
            return $this->backgroundImage->thumbnail_url;
        }
        return $this->background_image;
    }

    public function getGiftTypeLabelAttribute()
    {
        return $this->gift_type === self::GIFT_TYPE_WALLET ? 'Wallet Credit' : 'Order';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_USED => 'Used',
            self::STATUS_EXPIRED => 'Expired',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    // Methods
    public function isWalletType()
    {
        return $this->gift_type === self::GIFT_TYPE_WALLET;
    }

    public function isOrderType()
    {
        return $this->gift_type === self::GIFT_TYPE_ORDER;
    }

    public function isRedeemed()
    {
        return $this->status === self::STATUS_USED;
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function canBeRedeemed()
    {
        return $this->status === self::STATUS_ACTIVE &&
               !$this->isExpired() &&
               $this->redemption_method === self::REDEMPTION_PENDING;
    }

    public function redeemToWallet()
    {
        if (!$this->canBeRedeemed() || !$this->isWalletType()) {
            return false;
        }

        // Ensure we have a valid user_id
        $userId = $this->user_id ?: $this->recipient_id ?: $this->sender_id;

        if (!$userId) {
            throw new \InvalidArgumentException('No valid user ID found for wallet redemption');
        }

        // Create wallet transaction
        $transaction = Transaction::create([
            'user_id' => $userId,
            'transaction_number' => 'TXN-' . strtoupper(uniqid()),
            'amount' => $this->amount,
            'operation' => 'Deposit',
            'transaction_type' => 'recharge',
            'transaction_date' => now(),
            'status' => 'completed',
            'notes' => "Gift card redemption: {$this->code}",
        ]);

        $this->update([
            'status' => self::STATUS_USED,
            'redemption_method' => self::REDEMPTION_WALLET,
            'wallet_transaction_id' => $transaction->id,
            'redeemed_at' => now(),
        ]);

        return true;
    }

    public function createOrder()
    {
        if (!$this->canBeRedeemed() || !$this->isOrderType()) {
            return false;
        }

        // Use recipient_id if available, otherwise user_id, otherwise sender_id
        $orderUserId = $this->recipient_id ?: $this->user_id ?: $this->sender_id;

        if (!$orderUserId) {
            throw new \InvalidArgumentException('No valid user ID found for order creation');
        }

        // Create order based on product or offer
        $orderData = [
            'user_id' => $orderUserId,
            'vendor_id' => $this->vendor_id,
            'total' => $this->amount,
            'status' => \App\Enums\OrderStatus::Pending->value,
            'gift_card_id' => $this->id,
        ];

        if ($this->product_id) {
            $orderData['product_id'] = $this->product_id;
            $orderData['type'] = 'product';
        } elseif ($this->offer_id) {
            $orderData['offer_id'] = $this->offer_id;
            $orderData['type'] = 'offer';
        }

        $order = Order::create($orderData);

        $this->update([
            'status' => self::STATUS_USED,
            'redemption_method' => self::REDEMPTION_ORDER,
            'order_id' => $order->id,
            'redeemed_at' => now(),
        ]);

        return $order;
    }

    public function cancelOrderAndRedeemToWallet()
    {
        if (!$this->order || $this->redemption_method !== self::REDEMPTION_ORDER) {
            return false;
        }

        // Cancel the order using the correct enum value
        $this->order->update(['status' => \App\Enums\OrderStatus::Canceled->value]);

        // Redeem to wallet
        return $this->redeemToWallet();
    }
}
