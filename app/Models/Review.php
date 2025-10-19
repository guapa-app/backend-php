<?php

namespace App\Models;

use Illuminate\Http\Request;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Review extends Model implements HasMedia
{
    use HasFactory, ListableTrait, InteractsWithMedia;

    protected $fillable = [
        'user_id', 'reviewable_id', 'reviewable_type', 'stars', 'comment',
    ];

    protected $casts = [
        'stars' => 'decimal:1', // Ensure stars is treated as a decimal with 1 decimal place
    ];

    protected $filterable = [
        'user_id', 'reviewable_id', 'reviewable_type', 'stars', 'show'
    ];

    protected $search_attributes = [
        'comment',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('before')->singleFile();
        $this->addMediaCollection('after')->singleFile();
    }

    /**
     * Register media conversions.
     *
     * @return void
     */
    public function registerMediaConversions(BaseMedia $media = null): void
    {
        $this->addMediaConversion('small')
            ->fit(Manipulations::FIT_CROP, 200, 200)
            ->performOnCollections('before', 'after');

        $this->addMediaConversion('medium')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->performOnCollections('before', 'after');

        $this->addMediaConversion('large')
            ->fit(Manipulations::FIT_MAX, 600, 600)
            ->performOnCollections('before', 'after');
    }

    public function imageBefore(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')
            ->where('collection_name', 'before');
    }

    public function imageAfter(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')
            ->where('collection_name', 'after');
    }

    /**
     * Get the reviewable entity that the review belongs to.
     */
    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the order if the review is for an order.
     * This maintains backward compatibility with existing code.
     */
    public function order()
    {
        return $this->reviewable()
            ->where('reviewable_type', 'App\\Models\\Order');
    }

    /**
     * Get the consultation if the review is for a consultation.
     */
    public function consultation()
    {
        return $this->reviewable()
            ->where('reviewable_type', 'App\\Models\\Consultation');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(ReviewRating::class);
    }

    public function vendor()
    {
        // For backward compatibility, we need to check if the review is for an order
        return $this->hasOneThrough(
            Vendor::class,
            Order::class,
            'id', // Foreign key on orders table
            'id', // Foreign key on vendors table
            'reviewable_id', // Local key on reviews table (previously order_id)
            'vendor_id' // Local key on orders table
        )->where('reviewable_type', 'App\\Models\\Order');
    }

    public function products()
    {
        // For backward compatibility, we need to check if the review is for an order
        return $this->hasManyThrough(
            Product::class,
            OrderItem::class,
            'order_id', // Foreign key on order_items table
            'id', // Foreign key on products table
            'reviewable_id', // Local key on reviews table (previously order_id)
            'product_id' // Local key on order_items table
        )->where('reviewable_type', 'App\\Models\\Order');
    }

    public function scopeCurrentVendor($query, $value)
    {
        return $query->where(function($query) use ($value) {
            // For Order reviews
            $query->where('reviewable_type', 'App\\Models\\Order')
                ->whereHas('reviewable', function (Builder $query) use ($value) {
                    $query->where('vendor_id', $value);
                });
            
            // For Consultation reviews, if consultations have vendor_id
            // Uncomment and adjust if needed
            // $query->orWhere('reviewable_type', 'App\\Models\\Consultation')
            //     ->whereHas('reviewable', function (Builder $query) use ($value) {
            //         $query->where('vendor_id', $value);
            //     });
        });
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

        // Add filter for reviewable_type if needed
        if ($request->has('reviewable_type')) {
            $query->where('reviewable_type', $request->get('reviewable_type'));
        }

        return $query;
    }

    public function scopeWithListRelations(Builder $query, Request $request): Builder
    {
        $query->with([
            'user',
            'reviewable'
        ]);

        return $query;
    }

    public function scopeWithApiListRelations(Builder $query, Request $request): Builder
    {
        $query->with([
            'user',
            'reviewable',
            'imageBefore',
            'imageAfter',
        ]);

        return $query;
    }

    public function scopeWithListCounts(Builder $query, Request $request): Builder
    {
        return $query;
    }

    public function scopeWithSingleRelations(Builder $query): Builder
    {
        $query->with([
            'user',
            'reviewable',
            'imageBefore',
            'imageAfter',
            'ratings'
        ]);

        return $query;
    }
}