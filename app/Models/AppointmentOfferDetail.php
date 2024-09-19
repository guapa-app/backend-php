<?php

namespace App\Models;

use App\Enums\AppointmentOfferEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentOfferDetail extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'status' => AppointmentOfferEnum::class,
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function appointmentOffer(): BelongsTo
    {
        return $this->belongsTo(AppointmentOffer::class);
    }

    public function subVendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
