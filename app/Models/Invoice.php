<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoiceable_id', 'invoiceable_type', 'invoice_id', 'status',
        'amount', 'currency', 'amount_format', 'description',
        'expired_at', 'logo_url', 'url', 'callback_url', 'taxes',
        'invoiceable_id', 'invoiceable_type'
    ];

    protected $appends = [
        'vendor_name',
        'vendor_reg_num',
        'amount_without_taxes',
    ];

    public function invoiceable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getVendorNameAttribute(): string
    {
        return $this->invoiceable?->vendor?->name ?? '';
    }

    public function getVendorRegNumAttribute(): string
    {
        return $this->invoiceable?->vendor?->reg_number ?? '';
    }

    public function getAmountWithoutTaxesAttribute(): float
    {
        return $this->amount - $this->taxes;
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'invoiceable_id');
    }

    public function marketing_campaign(): BelongsTo
    {
        return $this->belongsTo(MarketingCampaign::class)->withDefault();
    }

    public function scopeCurrentVendor($query, $vendorId): void
    {
        $query->whereHasMorph('invoiceable', [Order::class, MarketingCampaign::class], function ($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        });
    }

    public function orderItems()
    {
        return $this->hasManyThrough(
            OrderItem::class,
            Order::class,
            'id', // Foreign key on orders table
            'order_id', // Foreign key on order_items table
            'invoiceable_id', // Local key on invoices table
            'id' // Local key on orders table
        );
    }
}
