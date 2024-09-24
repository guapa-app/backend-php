<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class OrderItem extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id', 'order_id', 'product_id', 'offer_id',
        'quantity', 'amount', 'amount_to_pay',
        'taxes', 'title', 'appointment',
    ];

    protected $appends = [
        'coupon_num',
    ];

    // =========== Attributes Section ===========
    public function getAppointmentAttribute($appointment)
    {
        return $appointment == null ? null : json_decode($appointment);
    }

    public function getCouponNumAttribute()
    {
        return rand(1, 100) . '-' . $this->order_id . '-' .
            rand(1, 100) . '-' . $this->id . '-' .
            rand(1, 100) . '-' . $this->product_id;
    }

    // =========== Methods Section ===========
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('order_items');
    }

    public function registerMediaConversions(BaseMedia $media = null): void
    {
        $this->addMediaConversion('small')
            ->fit(Manipulations::FIT_MAX, 300, 500)
            ->performOnCollections('order_items');

        $this->addMediaConversion('medium')
            ->fit(Manipulations::FIT_MAX, 600, 1000)
            ->performOnCollections('order_items');

        $this->addMediaConversion('large')
            ->fit(Manipulations::FIT_MAX, 900, 1500)
            ->performOnCollections('order_items');
    }

    public function qrCode(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')
            ->where('collection_name', 'order_items');
    }

    // =========== Relations Section ===========
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    // =========== Scopes Section ===========
    public function scopeCurrentVendor($query, $value)
    {
        return $query->whereRelation('product', 'vendor_id', '=', $value);
    }
}
