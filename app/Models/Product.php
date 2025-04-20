<?php

namespace App\Models;

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

class Product extends Model implements Listable, HasMedia, HasReviews
{
    use HasFactory,
        ListableTrait,
        InteractsWithMedia,
        HasTaxonomies,
        Reviewable,
        Likable,
        Relatable,
        SoftDeletes;

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
        'description',
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

        // Fetch the authenticated user's points
        $user = auth()->user();
        $userPoints = 0;

        if ($user) {
            try {
                $pointsWallet = $user->myPointsWallet; // Call the method without ()
                $userPoints = $pointsWallet ? (int) $pointsWallet->points : 0; // Ensure points is an integer
            } catch (\Exception $e) {
                // Log the error for debugging
                \Log::error('Error fetching user points: ' . $e->getMessage());
                $userPoints = 0;
                return [
                    'error' => 'Failed to fetch user points.',
                ];
            }
        }

        // Get the points-to-cash conversion rate (e.g., 100 points = 1 SR)
        $exchangeRate = (float) Setting::pointsConversionRate() ?: 100; // Default to 100 if not set
        $pointsValueInSr = $userPoints / $exchangeRate; // Convert points to SR (e.g., 1000 points = 10 SR, no rounding)

        // Calculate the points-based discount (in SR)
        $pointsDiscount = min($fees, $pointsValueInSr); // SR amount to discount using points
        $pointsUsed = (int) ($pointsDiscount * $exchangeRate); // Convert SR back to points as an integer
        $pointsDiscount = round($pointsDiscount, 2); // Round the SR value for consistency in the response

        // Apply the points discount to the fees
        $feesAfterPointsDiscount = max(0, $fees - $pointsDiscount); // Ensure fees don't go below 0

        // Recalculate taxes, remaining, and fees_with_taxes based on the adjusted fees
        $taxesAfterPointsDiscount = round(($taxPercentage / 100) * $feesAfterPointsDiscount, 2);
        $remainingAfterPointsDiscount = round($finalPrice - $feesAfterPointsDiscount, 2);
        $feesWithTaxesAfterPointsDiscount = round(($this->vendor->activate_wallet ? $finalPrice : $feesAfterPointsDiscount) + $taxesAfterPointsDiscount, 2);

        // Update the user's points (deduct the used points)
        if ($user && $pointsUsed > 0) {
            try {
                $pointsWallet = $user->myPointsWallet; // Call the method without ()
                if ($pointsWallet) {
                    $pointsWallet->points = (int) ($pointsWallet->points - $pointsUsed); // Ensure points remains an integer
                    $pointsWallet->save();
                }
            } catch (\Exception $e) {
                // Log the error for debugging
                \Log::error('Error updating user points: ' . $e->getMessage());
                return [
                    'error' => 'Failed to update user points.',
                ];
            }
        }

        return [
            'fees' => $this->vendor->activate_wallet ? $finalPrice : $fees, // Original fees before points discount
            'taxes' => $taxes, // Original taxes before points discount
            'remaining' => $this->vendor->activate_wallet ? 0 : $remaining, // Original remaining before points discount
            'fees_with_taxes' => $feesWithTaxes, // Original fees_with_taxes before points discount
            'tax_percentage' => $taxPercentage,
            'price_after_discount' => $this->offer ? round($this->offer_price, 2) : round((float) $this->price, 2),
            'discount_percentage' => round((float) $this->offer?->discount ?? 0.0, 2),
            // New parameters for points discount
            'points_discount' => $pointsDiscount, // SR amount discounted using points
            'points_used' => $pointsUsed, // Number of points used (integer)
            'fees_after_points_discount' => $this->vendor->activate_wallet ? $finalPrice : $feesAfterPointsDiscount, // Fees after points discount
            'taxes_after_points_discount' => $taxesAfterPointsDiscount, // Taxes after points discount
            'remaining_after_points_discount' => $this->vendor->activate_wallet ? 0 : $remainingAfterPointsDiscount, // Remaining after points discount
            'fees_with_taxes_after_points_discount' => $feesWithTaxesAfterPointsDiscount, // Fees with taxes after points discount
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

        if ($request->has('category_ids')) {
            $query->hasAnyTaxonomy((array) $request->get('category_ids'));
        }

        if ($request->has('category_id')) {
            $query->hasAnyTaxonomy([$request->get('category_id')]);
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
     *
     * @param  Builder  $query
     * @param  float  $lat
     * @param  float  $lng
     * @param  int  $dist
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
        $paidAmount = $this->payment_details['fees_with_taxes'];
        $points = (int) ($paidAmount * $conversionRate);
        return $points;
    }
}
