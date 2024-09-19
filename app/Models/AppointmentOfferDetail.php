<?php

namespace App\Models;

use App\Enums\AppointmentOfferEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AppointmentOfferDetail extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'status' => AppointmentOfferEnum::class,
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }

    public function appointmentOffer(): BelongsTo
    {
        return $this->belongsTo(AppointmentOffer::class);
    }

    public function subVendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
