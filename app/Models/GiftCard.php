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
        'type',
        'product_id',
        'offer_id',
        'amount',
        'currency',
        'background_color',
        'background_image',
        'message',
        'status',
        'expires_at',
        'redeemed_at',
        'recipient_name',
        'recipient_email',
        'recipient_number',
        'product_type',
    ];

    // Status constants
    public const STATUS_ACTIVE = 'active';
    public const STATUS_USED = 'used';
    public const STATUS_EXPIRED = 'expired';

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

    // Register media collections for background image
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gift_card_backgrounds')->singleFile();
    }

    protected static function booted()
    {
        static::saving(function ($giftCard) {
            if ($giftCard->type === 'product') {
                $giftCard->offer_id = null;
            } elseif ($giftCard->type === 'offer') {
                $giftCard->product_id = null;
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
}
