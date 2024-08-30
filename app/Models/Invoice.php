<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'campaign_id',
        'invoice_id',
        'status',
        'amount',
        'currency',
        'amount_format',
        'description',
        'expired_at',
        'logo_url',
        'url',
        'callback_url',
        'taxes',
    ];

    protected $appends = [
        'vendor_name',
        'vendor_reg_num',
        'amount_without_taxes',
    ];

    public function getVendorNameAttribute()
    {
        return $this->order ? $this->order->vendor->name :
            ($this->marketing_campaign ? $this->marketing_campaign->vendor->name : null);
    }

    public function getVendorRegNumAttribute()
    {
        return $this->order ? $this->order->vendor->reg_number :
            ($this->marketing_campaign ? $this->marketing_campaign->vendor->reg_number : null);
    }

    public function getAmountWithoutTaxesAttribute()
    {
        return $this->amount - $this->taxes;
    }



    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function marketing_campaign()
    {
        return $this->belongsTo(MarketingCampaign::class);
    }
//    public function scopeCurrentVendor($query, $value)
//    {
//        return $query->whereRelation('order', 'vendor_id', '=', $value);
//    }
    public function scopeCurrentVendor($query, $vendorId)
    {
        return $query->where(function($query) use ($vendorId) {
            $query->whereHas('order', function($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })->orWhereHas('campaign', function($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            });
        });
    }
}
