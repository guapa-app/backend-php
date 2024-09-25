<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\Request;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class History extends Model implements Listable, HasMedia
{
    use HasFactory, ListableTrait, InteractsWithMedia;

    protected $table = 'history';

    protected $fillable = [
        'user_id', 'details', 'record_date',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'media',
    ];

    protected $casts = [
        'record_date' => 'date',
    ];

    protected $filterable = [
        'user_id',
    ];

    protected $search_attributes = [
        'details',
    ];

    /**
     * Register media collections.
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('history_images')->singleFile();
    }

    /**
     * Register media conversions.
     * @return void
     */
    public function registerMediaConversions(BaseMedia $media = null): void
    {
        $this->addMediaConversion('small')
            ->fit(Manipulations::FIT_CROP, 100, 100)
            ->performOnCollections('history_images');

        $this->addMediaConversion('medium')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->performOnCollections('history_images');

        $this->addMediaConversion('large')
            ->fit(Manipulations::FIT_CROP, 600, 600)
            ->performOnCollections('history_images');
    }

    public function getDetailsAttribute()
    {
        return strip_tags($this->attributes['details'] ?? '');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * History image relationship.
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function image(): MorphOne
    {
        return $this->morphOne('App\Models\Media', 'model')
            ->where('collection_name', 'history_images');
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

        if ($request->has('date')) {
            $query->whereDate('record_date', \Carbon\Carbon::parse($request->get('date')));
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
        $query->with('image');

        return $query;
    }
}
