<?php

namespace App\Models;

use App\Enums\AppointmentOfferEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class AppointmentOffer extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => AppointmentOfferEnum::class,
    ];

    /**
     * Register media conversions.
     *
     * @return void
     */
    public function registerMediaConversions(BaseMedia $media = null): void
    {
        $this->addMediaConversion('small')
            ->fit(Manipulations::FIT_MAX, 300, 500)
            ->performOnCollections('products');

        $this->addMediaConversion('medium')
            ->fit(Manipulations::FIT_MAX, 600, 1000)
            ->performOnCollections('products');

        $this->addMediaConversion('large')
            ->fit(Manipulations::FIT_MAX, 900, 1500)
            ->performOnCollections('products');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(AppointmentOfferDetail::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function taxonomy(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class);
    }

    public function appointmentForms(): BelongsToMany
    {
        return $this->belongsToMany(AppointmentForm::class, 'appointment_offer_form')
            ->withPivot('key', 'answer', 'appointment_form_value_id')
            ->withTimestamps();
    }

    public function invoices(): MorphOne
    {
        return $this->morphOne(Invoice::class, 'invoiceable');
    }

    public function scopeWithSingleRelations(Builder $query): void
    {
        $query->with([
            'vendor.logo', 'taxonomy', 'details.subVendor', 'appointmentForms.values', 'media'
        ]);
    }
}
