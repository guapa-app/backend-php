<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Helpers\Common;
use App\Traits\Likable;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Offer extends Model implements Listable, HasMedia
{
    use HasFactory, ListableTrait, InteractsWithMedia ,Likable;

    protected $fillable = [
        'product_id', 'discount', 'title', 'description',
        'terms', 'starts_at', 'expires_at',
    ];

    protected $appends = [
        'discount_string', 'status',
        'expires_countdown', 'price',
    ];

    protected $search_attributes = [
        'title', 'description',
    ];

    protected $filterable_attributes = [
        'product_id',
    ];

    protected $casts = [
        'starts_at'  => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Register media collections.
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('offer_images')->singleFile();
    }

    /**
     * Register media conversions.
     * @param BaseMedia|null $media
     * @return void
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(BaseMedia $media = null): void
    {
        $this->addMediaConversion('small')
            ->fit(Manipulations::FIT_MAX, 300, 500)
            ->performOnCollections('offer_images');

        $this->addMediaConversion('medium')
            ->fit(Manipulations::FIT_MAX, 600, 1000)
            ->performOnCollections('offer_images');

        $this->addMediaConversion('large')
            ->fit(Manipulations::FIT_MAX, 900, 1500)
            ->performOnCollections('offer_images');
    }

    public function getExpiresCountdownAttribute(): string
    {
        $difference = Carbon::parse($this->expires_at)->diff(now());

        $daysString = Common::getLocalizedUnitString($difference->days, 'day');

        return __('api.the_offer_expires_in', ['countdown' => $daysString]);
    }

    public function getPriceAttribute(): float
    {
        return round($this->product->price * (1 - ($this->discount / 100)), 1);
    }

    public function getDescriptionAttribute()
    {
        return nl2br($this->attributes['description'] ?? '');
    }

    public function setDescriptionAttribute($value)
    {
        $value = str_replace(
            ['<p>', '</p>', '<br>', '<br/>', '<br />'],
            ['', "\n", "\n", "\n", "\n"],
            $value
        );

        $this->attributes['description'] = strip_tags($value);
    }

    public function getDiscountStringAttribute()
    {
        return $this->discount . '%';
    }

    public function getStatusAttribute()
    {
        if ($this->starts_at == null) {
            return 'Active';
        } elseif (now()->lt($this->starts_at)) {
            return 'Incoming';
        } elseif (now()->gt($this->expires_at)) {
            return 'Expired';
        } else {
            return 'Active';
        }
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed()->withDefault();
    }

    /**
     * Offer image relationship.
     * @return MorphOne
     */
    public function image(): MorphOne
    {
        return $this->morphOne('App\Models\Media', 'model')
            ->where('collection_name', 'offer_images');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'offer_id');
    }

    public function scopeActive($query): Builder
    {
        return $query->whereNull('starts_at')
            ->orWhere(function ($q) {
                $q->where('starts_at', '<=', now())
                    ->where('expires_at', '>=', now());
            });
    }

    public function scopeIncoming($query): Builder
    {
        return $query->where('starts_at', '>', now());
    }

    public function scopeExpired($query): Builder
    {
        return $query->where('expires_at', '<', now());
    }

    public function scopeApplyFilters(Builder $query, Request $request): Builder
    {
        $filter = $request->get('filter');
        if (is_array($filter)) {
            $request = new Request($filter);
        }

        $query->dateRange($request->get('startDate'), $request->get('endDate'));

        $query->searchLike($request);

        $query->applyDirectFilters($request);

        if ($request->has('status')) {
            $status = $request->get('status');
            if (in_array($status, ['active', 'incoming', 'expired'])) {
                $query->$status();
            }
        }

        return $query;
    }

    public function scopeWithListRelations(Builder $query, Request $request): Builder
    {
        return $query;
    }

    public function scopeWithApiListRelations(Builder $query, Request $request): Builder
    {
        $query->with('image');

        return $query;
    }

    public function scopeWithListCounts(Builder $query, Request $request): Builder
    {
        return $query;
    }

    public function scopeWithSingleRelations(Builder $query): Builder
    {
        $query->with('product', 'image');

        return $query;
    }

    public function scopeCurrentVendor($query, $value)
    {
        return $query->whereRelation('product', 'vendor_id', '=', $value);
    }
}
