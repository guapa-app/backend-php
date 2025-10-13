<?php

namespace App\Models;

use App\Enums\OrderStatus;
use DB;
use App\Traits\Likable;
use App\Enums\ProductType;
use App\Traits\Reviewable;
use App\Contracts\Listable;
use App\Enums\ProductReview;
use App\Enums\ProductStatus;
use Illuminate\Http\Request;
use App\Contracts\HasReviews;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Scopes\CountryScope;
use Hamedov\Taxonomies\HasTaxonomies;
use Hamedov\Messenger\Traits\Relatable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;
use App\Services\LoyaltyPointsService;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements Listable, HasMedia, HasReviews
{
    use HasFactory,
        ListableTrait,
        InteractsWithMedia,
        HasTaxonomies,
        Reviewable,
        Likable,
        Relatable,
        HasTranslations,
        SoftDeletes;

    protected $translatable = [
        'title',
        'description',
        'terms',
    ];

    protected $fillable = [
        'country_id',
        'hash_id',
        'vendor_id',
        'title',
        'description',
        'price',
        'earned_points',
        'status',
        'review',
        'type',
        'stock',
        'is_shippable',
        'min_quantity_per_user',
        'max_quantity_per_user',
        'days_of_delivery',
        'terms',
        'url',
        'sort_order',
    ];

    protected $appends = [
        'likes_count',
        'is_liked',
        'taxonomy_name',
        'taxonomy_id',
        'taxonomy_type',
        'address',
        'shared_link',
    ];

    /**
     * Attributes that can be filtered directly
     * using values from client without any logic.
     *
     * @var array
     */
    protected $filterable = [
        'status',
        'review',
        'type',
        'vendor_id',
    ];

    /**
     * Attributes to be searched using like operator.
     *
     * @var array
     */
    protected $search_attributes = [
        'hash_id',
        'title',
    ];

    protected $casts = [
        'type' => ProductType::class,
        'status' => ProductStatus::class,
        'review' => ProductReview::class,
    ];

    protected static function booted()
    {
        static::addGlobalScope(new CountryScope());
    }

    /**
     * Register media collections.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('products');
    }

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

    public function getAddressAttribute()
    {
        $countryName = $this->vendor?->address?->city?->country?->name;

        $city = $this->vendor?->address?->city?->name;

        return $city ? "$city - $countryName" : $this->country?->name;
    }

    public function getSharedLinkAttribute()
    {
        if($this->shareLink){
            $key = $this->shareLink->shareable_id;
            // ref the first char of model name (v or p)
            $ref = strtolower(substr($this->shareLink->shareable_type, 0, 1));
            return $this->shareLink->link . "?ref={$ref}&key={$key}";
        }
        return $this->shareLink?->link;
    }

    public function getTaxonomyNameAttribute()
    {
        return $this->getRelations()['taxonomies'][0]->title ?? '';
    }

    public function getTaxonomyIdAttribute()
    {
        return $this->getRelations()['taxonomies'][0]->id ?? '';
    }

    public function getTaxonomyTypeAttribute()
    {
        return $this->getRelations()['taxonomies'][0]->type ?? '';
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

    public function getOfferPriceAttribute()
    {
        $price = $this->price;
        if ($this->offer) {
            $price -= ($price * ($this->offer->discount / 100));
            $price = round($price, 2);
        }

        return $price;
    }

    public function getPaymentDetailsAttribute()
    {
        $finalPrice = round((float) $this->offer_price, 2); // Use the getOfferPriceAttribute method
        $fees = round((float) $this->calculateProductFees($finalPrice), 2);
        $taxPercentage = (float) Setting::getTaxes(); // Tax percentage (default: 15%)
        $taxes = round(($taxPercentage / 100) * $fees, 2);
        $remaining = round($finalPrice - $fees, 2);
        $feesWithTaxes = round(($this->vendor->activate_wallet ? $finalPrice : $fees) + $taxes, 2);
        $remainingTaxes = $this->type == ProductType::Service ? 0 : round($remaining * ($taxPercentage / 100) , 2);
        $remainingWithTaxes = round($remaining + $remainingTaxes , 2);

        return [
            'fixed_price' => round((float) $this->price, 2),
            'fixed_price_with_discount' => $this->offer ? round($this->offer_price, 2) : round((float) $this->price, 2),
            'guapa_fees' => $this->vendor->activate_wallet ? $finalPrice : $fees,
            'guapa_fees_with_taxes' => $feesWithTaxes,
            'vendor_price_with_taxes' => $remainingWithTaxes,
            'total_amount_with_taxes' => round($remainingWithTaxes + $feesWithTaxes, 2),
        ];
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function shareLink(): MorphOne
    {
        return $this->morphone(ShareLink::class, 'shareable')->withDefault();
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

    public function addresses(): BelongsToMany
    {
        return $this->belongsToMany(Address::class, 'product_addresses');
    }

    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class, 'coupon_products');
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')
            ->where('collection_name', 'products');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    public function marketingCampaigns(): MorphMany
    {
        return $this->morphMany(MarketingCampaign::class, 'campaignable');
    }

    public function loyaltyPointHistories()
    {
        return $this->morphMany(LoyaltyPointHistory::class, 'sourceable');
    }

    public function scopeCurrentVendor($query, $value): void
    {
        $query->where('vendor_id', $value);
    }

    public function scopeService($query): void
    {
        $query->where('type', ProductType::Service);
    }

    public function scopeProduct($query): void
    {
        $query->where('type', ProductType::Product);
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

        if ($request->has('vendor_ids')) {
            $query->whereIn('vendor_id', $request->vendor_ids);
        }

        if ($request->has('category_ids')) {
            $query->hasAnyTaxonomy((array) $request->get('category_ids'));
        }

        // Filter by price range
        if ($request->hasAny(['min_price', 'max_price'])) {
            $query->priceRange($request->get('min_price'), $request->get('max_price'));
        }

        if ($request->has('city_ids')) {
            $cityIds = $request->get('city_ids');
            $query->whereHas('vendor.addresses', function ($q) use ($cityIds) {
                $q->whereIn('city_id', $cityIds);
            });
        }

        // Get products nearby specific location by specific distance and filter by distance range 
        if ($request->has('lat') && $request->has('lng')) {
            $query->nearBy($request->get('lat'), $request->get('lng'), $request->get('distance'), $request->get('min_distance'), $request->get('max_distance'));
        }


        // // Get best selling products
        // if ($request->has('best_selling') && $request->get('best_selling')) {
        //     $query->bestSelling();
        // }

        $user = app('cosmo')->user();
        // We need to return only active products owned by active vendors
        // Excluding admins and vendors displaying their own products.
        $currentUserWorksForFilteredVendor = $user && !$user->isAdmin() && $request->has('vendor_id')
            && $user->hasVendor((int) $request->vendor_id);
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
     *
     * @param  Builder  $query
     * @param  float  $lat
     * @param  float  $lng
     * @param  ?int  $dist
     * @param  ?float  $minDistance
     * @param  ?float  $maxDistance
     * @return Builder
     */
    public function scopeNearBy($query, $lat, $lng, $dist = null, ?float $minDistance = null, ?float $maxDistance = null)
    {
        $query->select('products.*');

        // Join with vendors and only the nearest address for each vendor
        $query->join('vendors', function ($join) {
            $join->on('products.vendor_id', '=', 'vendors.id');
        })
        ->join('addresses', function ($join) use ($lat, $lng) {
            $join->on('addresses.addressable_id', '=', 'vendors.id')
                 ->whereRaw('(6371 * acos(cos(radians(' . $lat . ')) * cos(radians(addresses.lat)) * cos(radians(addresses.lng) - radians(' . $lng . ')) + sin(radians(' . $lat . ')) * sin(radians(addresses.lat)))) = (
                     SELECT MIN((6371 * acos(cos(radians(' . $lat . ')) * cos(radians(a2.lat)) * cos(radians(a2.lng) - radians(' . $lng . ')) + sin(radians(' . $lat . ')) * sin(radians(a2.lat)))))
                     FROM addresses a2 
                     WHERE a2.addressable_id = vendors.id AND a2.addressable_type = "vendor"
                 )');
        })
        ->where('addresses.addressable_type', 'vendor');

        $distance_aggregate = "(6371 * acos(cos(radians($lat)) * cos(radians(addresses.lat)) * cos(radians(addresses.lng) - radians($lng)) + sin(radians($lat)) * sin(radians(addresses.lat))))";

        $query->addSelect(DB::raw($distance_aggregate . ' AS distance'));

        if($dist){
            $dist = (int) $dist < 1 ? 50 : $dist;

            $query->havingRaw("distance <= {$dist}");
        }

        $query->when($minDistance, fn($query) => $query->havingRaw("distance >= {$minDistance}"))
            ->when($maxDistance, fn($query) => $query->havingRaw("distance <= {$maxDistance}"));

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
            'vendor',
            'vendor.logo',
            'vendor.addresses',
            'offer',
            'offer.image',
            'media',
            'taxonomies',
            'vendor.appointments',
            'vendor.workDays',
        ]);

        return $query;
    }

    public function scopeBestSelling(Builder $query): Builder
    {
        return $query->withCount(['orderItems' => function ($query) {
            $query->whereHas('order', function ($query) {
                $query->where('status', OrderStatus::Accepted);
            });
        }])
            ->orderBy('order_items_count', 'desc');
        return $query;
    }

    public function calculateProductFees($finalPrice)
    {
        $productCategory = $this->taxonomies()->first();
        if (!$productCategory) {
            return 0;
        }
        $categoryCountryFees = $this->getCategoryCountryFees($productCategory->id, $finalPrice);

        if ($categoryCountryFees !== false) {
            return $categoryCountryFees;
        } else {
            if ($productCategory?->fees) {
                $productFees = $productCategory->fees;

                return ($productFees / 100) * $finalPrice;
            } else {
                return $productCategory?->fixed_price ?? 0;
            }
        }
    }

    public function getCategoryCountryFees($categoryId, $finalPrice)
    {
        $request = request();

        $country = $request->get('country'); // Get country from middleware CountryHeader

        if (!$country) {
            return false;
        }

        $fees = CategoryFee::where('category_id', $categoryId)
            ->where('country_id', $country->id)
            ->first();

        if (!$fees) {
            return false;
        }

        if ($fees?->fee_percentage) {
            $productFees = $fees->fee_percentage;
            return ($productFees / 100) * $finalPrice;
        } else {
            return $fees?->fee_fixed ?? 0;
        }
    }

    /**
     * Calc Product Points
     *
     * @return int
     */
    function calcProductPoints()
    {
        if ($this->earned_points > 0)
            return $this->earned_points;

        $conversionRate = Setting::purchasePointsConversionRate();
        $paidAmount = $this->payment_details['guapa_fees_with_taxes'];
        $points = (int) ($paidAmount * $conversionRate);
        return $points;
    }
}
