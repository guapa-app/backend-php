<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function invoiceable()
    {
        return $this->morphTo();
    }

    public function getVendorNameAttribute()
    {
        return $this->invoiceable?->vendor?->name ?? '';
    }

    public function getVendorRegNumAttribute()
    {
        return $this->invoiceable?->vendor?->reg_number ?? '';
    }

    public function getAmountWithoutTaxesAttribute()
    {
        return $this->amount - $this->taxes;
    }

    public function order()
    {
        return $this->belongsTo(Order::class)->withDefault();
    }

    public function marketing_campaign()
    {
        return $this->belongsTo(MarketingCampaign::class)->withDefault();
    }

    /**
     * Get the parent invoiceable model.
     */
    public function invoiceable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeCurrentVendor($query, $vendorId)
    {
        return $query->whereHasMorph('invoiceable', [Order::class, MarketingCampaign::class], function ($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        });
    }
}
