<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Enums\AppointmentOfferEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\Request;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Traits\Listable as ListableTrait;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class AppointmentOffer extends Model implements Listable, HasMedia
{
    use  ListableTrait, InteractsWithMedia;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => AppointmentOfferEnum::class,
    ];

    /**
     * Attributes that can be filtered directly
     * using values from client without any logic.
     *
     * @var array
     */
    protected $filterable = [
        'status'
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

    public function details(): HasMany
    {
        return $this->hasMany(AppointmentOfferDetail::class)->with('vendor');
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
            ->withPivot('key', 'answer')
            ->withTimestamps();
    }

    public function invoices(): MorphOne
    {
        return $this->morphOne(Invoice::class, 'invoiceable');
    }

    public function scopeApplyFilters(Builder $query, Request $request): Builder
    {
        $filter = $request->get('filter');
        if (is_array($filter)) {
            $request = new Request($filter);
        }

        $query->searchLike($request);

        $query->applyDirectFilters($request);

        return $query;
    }

    public function scopeWithSingleRelations(Builder $query): Builder
    {
        $query->with([
            'taxonomy', 'details', 'appointmentForms', 'media'
        ]);
        return $query;
    }

    public function scopeWithListRelations(Builder $query, Request $request): Builder
    {
        return $query;
    }

    public function scopeWithListCounts(Builder $query, Request $request): Builder
    {
        return $query;
    }
}
