<?php

namespace App\Models;

use App\Enums\AppointmentOfferEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;
use Spatie\Translatable\HasTranslations;

class AppointmentOfferDetail extends Model  implements HasMedia
{
    use  InteractsWithMedia, HasTranslations;

    protected $guarded = ['id'];

    protected $translatable = [
        'terms'
    ];

    protected $casts = [
        'status' => AppointmentOfferEnum::class,
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('appointment_details');
    }

    public function registerMediaConversions(BaseMedia $media = null): void
    {
        $this->addMediaConversion('small')
            ->fit(Manipulations::FIT_MAX, 300, 500)
            ->performOnCollections('appointment_details');

        $this->addMediaConversion('medium')
            ->fit(Manipulations::FIT_MAX, 600, 1000)
            ->performOnCollections('appointment_details');

        $this->addMediaConversion('large')
            ->fit(Manipulations::FIT_MAX, 900, 1500)
            ->performOnCollections('appointment_details');
    }

    public function qrCode(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')
            ->where('collection_name', 'appointment_details');
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }

    public function appointmentOffer(): BelongsTo
    {
        return $this->belongsTo(AppointmentOffer::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
