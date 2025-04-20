<?php

namespace App\Models;

use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Review extends Model  implements HasMedia
{
    use HasFactory, ListableTrait, InteractsWithMedia;

    protected $fillable = [
        'user_id', 'order_id', 'stars', 'comment',
    ];

    protected $filterable = [
        'user_id', 'order_id', 'stars', 'show'
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

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
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
        return $this->hasOneThrough(
            Vendor::class,
            Order::class,
            'id', // Foreign key on orders table
            'id', // Foreign key on vendors table
            'order_id', // Local key on reviews table
            'vendor_id' // Local key on orders table
        );
    }

    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            OrderItem::class,
            'order_id', // Foreign key on order_items table
            'id', // Foreign key on products table
            'order_id', // Local key on reviews table
            'product_id' // Local key on order_items table
        );
    }

    public function scopeCurrentVendor($query, $value)
    {
        return $query->whereHas('order', function (Builder $query) use ($value) {
            $query->where('vendor_id', $value);
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

        return $query;
    }

    public function scopeWithListRelations(Builder $query, Request $request): Builder
    {
        $query->with([
            'user',
            'order',
            'order.vendor',
            'order.items.product'
        ]);

        return $query;
    }

    public function scopeWithApiListRelations(Builder $query, Request $request): Builder
    {

        $query->with([
            'user',
            'order',
            'order.vendor',
            'order.items.product',
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
            'order',
            'order.vendor',
            'order.items.product',
            'imageBefore',
            'imageAfter',
            'ratings'
        ]);

        return $query;
    }
}
