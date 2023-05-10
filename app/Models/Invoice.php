<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
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
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeCurrentVendor($query, $value)
    {
        return $query->whereRelation('order', 'vendor_id', '=', $value);
    }
}
