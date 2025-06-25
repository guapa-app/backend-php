<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
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
        'created_by',
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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
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
                $giftCard->code = strtoupper(uniqid('GC'));
            }

            // Set default status if not set
            if (empty($giftCard->status)) {
                $giftCard->status = self::STATUS_ACTIVE;
            }

            // Set default redemption method if not set
            if (empty($giftCard->redemption_method)) {
                $giftCard->redemption_method = self::REDEMPTION_PENDING;
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
        return $query->where('created_by', $userId);
    }

    public function scopeReceivedBy($query, $user)
    {
        return $query->where(function($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('recipient_email', $user->email)
              ->orWhere('recipient_number', $user->phone);
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

        // Create wallet transaction
        $transaction = Transaction::create([
            'user_id' => $this->user_id,
            'amount' => $this->amount,
            'type' => 'credit',
            'operation' => 'gift_card_redemption',
            'description' => "Gift card redemption: {$this->code}",
            'status' => 'completed',
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

        // Create order based on product or offer
        $orderData = [
            'user_id' => $this->user_id,
            'vendor_id' => $this->vendor_id,
            'total_amount' => $this->amount,
            'currency' => $this->currency,
            'status' => 'pending',
            'payment_method' => 'gift_card',
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

        // Cancel the order
        $this->order->update(['status' => 'cancelled']);

        // Redeem to wallet
        return $this->redeemToWallet();
    }
}
