<?php

namespace App\Models;

use App\Contracts\HasReviews;
use App\Contracts\Listable;
use App\Enums\ProductReview;
use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Traits\Likable;
use App\Traits\Listable as ListableTrait;
use App\Traits\Reviewable;
use DB;
use Hamedov\Messenger\Traits\Relatable;
use Hamedov\Taxonomies\HasTaxonomies;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Product extends Model implements Listable, HasMedia, HasReviews
{
    use HasFactory, ListableTrait, InteractsWithMedia, HasTaxonomies,
        Reviewable, Likable, Relatable, SoftDeletes;

    protected $fillable = [
        'hash_id', 'vendor_id', 'title', 'description', 'price',
        'status', 'review', 'type', 'terms', 'url',
    ];

    protected $appends = [
        'likes_count', 'is_liked',
        'taxonomy_name', 'address',
    ];

    /**
     * Attributes that can be filtered directly
     * using values from client without any logic.
     * @var array
     */
    protected $filterable = [
        'status', 'review', 'type', 'vendor_id',
    ];

    /**
     * Attributes to be searched using like operator.
     * @var array
     */
    protected $search_attributes = [
        'hash_id', 'title', 'description',
    ];

    protected $casts = [
        'type'   => ProductType::class,
        'status' => ProductStatus::class,
        'review' => ProductReview::class,
    ];

    /**
     * Register media collections.
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('products');
    }

    /**
     * Register media conversions.
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

    public function getAddressAttribute()
    {
        $countryName = request()->header('Accept-Language') == 'en' ? 'KSA' : 'السعودية';

        $city = $this->vendor?->address?->city?->name;

        return $city ? "$city - $countryName" : $countryName;
    }

    public function getTaxonomyNameAttribute()
    {
        return $this->getRelations()['taxonomies'][0]->title ?? '';
    }

    public function getCategoryIdsAttribute()
    {
        $relations = $this->getRelations();
        if (!empty($relations['categories'])) {
            return $relations['categories']->pluck('id')->toArray();
        }

        return [];
    }

    public function getAddressIdsAttribute()
    {
        $relations = $this->getRelations();
        if (!empty($relations['addresses'])) {
            return $relations['addresses']->pluck('id')->toArray();
        }

        return [];
    }

    public function getDistanceAttribute()
    {
        return isset($this->attributes['distance']) ?
            round($this->attributes['distance'], 1) :
            0;
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function offer(): HasOne
    {
        return $this->hasOne(Offer::class)->active();
    }

    public function oldCurrentUpcomingOffer(): HasOne
    {
        return $this->hasOne(Offer::class);
    }

    public function categories()
    {
        return $this->taxonomies('specialty');
    }

    public function addresses()
    {
        return $this->belongsToMany(Address::class, 'product_addresses');
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_products');
    }

    public function image()
    {
        return $this->morphOne(Media::class, 'model')
            ->where('collection_name', 'products');
    }

    public function scopeCurrentVendor($query, $value)
    {
        return $query->where('vendor_id', $value);
    }

    public function scopeService($query)
    {
        return $query->where('type', ProductType::Service);
    }

    public function scopeProduct($query)
    {
        return $query->where('type', ProductType::Product);
    }

    public function scopePriceRange($query, $minPrice, $maxPrice)
    {
        if (isset($minPrice)) {
            $query->where('products.price', '>=', $minPrice);
        }

        if (isset($maxPrice)) {
            $query->where('products.price', '<=', $maxPrice);
        }

        return $query;
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

        if ($request->has('category_ids')) {
            $query->hasAnyTaxonomy((array) $request->get('category_ids'));
        }

        // Filter by price range
        if ($request->hasAny(['min_price', 'max_price'])) {
            $query->priceRange($request->get('min_price'), $request->get('max_price'));
        }

        if ($request->has('city_id')) {
            $cityId = (int) $request->get('city_id');
            $query->whereHas('addresses', function ($q) use ($cityId) {
                $q->where('city_id', $cityId);
            });
        }

        // Get products nearby specific location by specific distance
        if ($request->has('lat') && $request->has('lng')) {
            $query->nearBy($request->get('lat'), $request->get('lng'), $request->get('distance'));
        }

        $user = app('cosmo')->user();
        // We need to return only active products owned by active vendors
        // Excluding admins and vendors displaying their own products.
        $currentUserWorksForFilteredVendor = $user && !$user->isAdmin() && $request->has('vendor_id')
            && $user->hasVendor((int) $request->get('vendor_id'));
        if (
            !$currentUserWorksForFilteredVendor &&
            (!$user || !$user->isAdmin())
        ) {
            $query->active();
        }

        if ($request->has('list_type')) {
            $listType = (string) $request->get('list_type');
            $status = (string) $request->get('status');
            $query->listType($listType, $currentUserWorksForFilteredVendor, $status);
        }

        return $query;
    }

    public function scopeActive($query)
    {
        $query->where([
            'products.status' => 'Published',
            'products.review' => 'Approved',
        ]);

        $query->whereHas('vendor', function ($q) {
            $q->where('vendors.status', '1');
            $q->where('vendors.verified', '1');
        });

        return $query;
    }

    /**
     * Scope the query to return only products nearby a specific location by specific distance.
     * @param Builder $query
     * @param float $lat
     * @param float $lng
     * @param int $dist
     * @return Builder
     */
    public function scopeNearBy($query, $lat, $lng, $dist = 50)
    {
        $query->select('products.*');

        $query->join('product_addresses', function ($join) {
            $join->on('products.id', '=', 'product_addresses.product_id');
        });

        if (!isset($dist) || (int) $dist < 1) {
            $dist = 50;
        }

        $distance_aggregate = "(6371 * acos(cos(radians($lat)) * cos(radians(addresses.lat)) * cos(radians(addresses.lng) - radians($lng)) + sin(radians($lat)) * sin(radians(addresses.lat))))";

        $query->addSelect(DB::raw($distance_aggregate . ' AS distance'));

        $query->join('addresses', function ($join) use ($lat, $lng, $dist) {
            $join->on('addresses.id', '=', 'product_addresses.address_id');
        });

        $query->havingRaw("distance <= {$dist}");

        // Need to make sure no order by is provided elsewhere
        $query->orderBy('distance', 'asc');

        return $query;
    }

    public function scopeListType($query, string $type, bool $userWorksForVendor, string $status)
    {
        if ($type === 'offers') {
            $query->whereHas('offer', function ($q) use ($userWorksForVendor, $status) {
                if (!$userWorksForVendor || $status === 'active') {
                    $q->active();
                } elseif ($status === 'expired') {
                    $q->expired();
                } elseif ($status === 'incoming') {
                    $q->incoming();
                }
            });
        }

        return $query;
    }

    public function scopeWithListRelations(Builder $query, Request $request): Builder
    {
        $query->with('vendor');

        return $query;
    }

    public function scopeWithApiListRelations(Builder $query, Request $request): Builder
    {
        $query->with('vendor.logo', 'media', 'offer', 'offer.image', 'taxonomies');

        return $query;
    }

    public function scopeWithAllVendorOffers(Builder $query, Request $request): Builder
    {
        $query->with('oldCurrentUpcomingOffer', 'oldCurrentUpcomingOffer.image');

        return $query;
    }

    public function scopeWithListCounts(Builder $query, Request $request): Builder
    {
        return $query;
    }

    public function scopeWithSingleRelations(Builder $query): Builder
    {
        $query->with([
            'vendor', 'vendor.logo', 'vendor.addresses', 'offer', 'offer.image',
            'media', 'taxonomies', 'vendor.appointments',
            'vendor.workDays',
        ]);

        return $query;
    }
}
