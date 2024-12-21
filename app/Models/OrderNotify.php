<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\ProductType;
use App\Contracts\Listable;
use App\Enums\OrderTypeEnum;
use Illuminate\Http\Request;
use App\Models\Scopes\CountryScope;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class OrderNotify extends Model
{
    use HasFactory, ListableTrait;

    protected $table = 'orders';

    protected $fillable = [
        'country_id',
        'hash_id',
        'user_id',
        'vendor_id',
        'address_id',
        'total',
        'status',
        'note',
        'name',
        'phone',
        'invoice_url',
        'cancellation_reason',
        'coupon_id',
        'discount_amount',
        'last_reminder_sent',
        'type',
        'staff_id',
        'payment_gateway',
        'payment_id'
    ];

    /**
     * Attributes that can be filtered directly
     * using values from client without any logic.
     *
     * @var array
     */
    protected $filterable = [
        'status',
        'user_id',
        'vendor_id',
        'type'
    ];

    /**
     * Attributes to be searched using like operator.
     *
     * @var array
     */
    protected $search_attributes = [
        'name',
        'phone',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    protected $appends = [
        'paid_amount_with_taxes',
        'paid_amount',
        'remaining_amount',
    ];

    public function getPaidAmountWithTaxesAttribute()
    {
        return $this->invoice?->amount ?? 0;
    }

    public function getPaidAmountAttribute()
    {
        return number_format(($this->invoice?->amount - $this->invoice?->taxes),
            decimal_separator: '',
            thousands_separator: ''
        );
    }

    public function getRemainingAmountAttribute(): float
    {
        return $this->total - ($this->paid_amount);
    }

    public function invoice(): MorphOne
    {
        return $this->morphOne(Invoice::class, 'invoiceable');
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
