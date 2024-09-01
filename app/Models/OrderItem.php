<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'product_id', 'amount', 'quantity',
        'appointment', 'offer_id', 'title', 'taxes', 'amount_to_pay',
    ];

    protected $appends = [
        'coupon_num',
    ];

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

    public function scopeCurrentVendor($query, $value)
    {
        return $query->whereRelation('product', 'vendor_id', '=', $value);
    }
}
