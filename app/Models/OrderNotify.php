<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderNotify extends Model
{
    use HasFactory, ListableTrait;

    protected $table = 'orders';

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    public function getPaidAmountWithTaxesAttribute()
    {
        return $this->invoice->amount ?: 0;
    }

    public function getPaidAmountAttribute()
    {
        return number_format(($this->invoice->amount - $this->invoice->taxes),
            decimal_separator: '',
            thousands_separator: ''
        );
    }

    public function getRemainingAmountAttribute(): float
    {
        return $this->total - ($this->paid_amount);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'invoiceable_id')->where('invoiceable_type', 'App\Models\Order');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
