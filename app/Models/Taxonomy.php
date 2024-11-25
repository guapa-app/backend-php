<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Traits\Listable as ListableTrait;
use Hamedov\Taxonomies\Taxonomy as BaseTaxonomy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\Request;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\SlugOptions;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Taxonomy extends BaseTaxonomy implements Listable
{
    use ListableTrait, HasRecursiveRelationships, HasFactory;

    protected $fillable = [
        'title', 'slug', 'fees',
        'fixed_price', 'description', 'font_icon',
        'type', 'is_appointment', 'appointment_price',
        'parent_id', 'sort_order', 'is_published',
    ];

    /**
     * Attributes that can be filtered directly
     * using values from client without any logic.
     *
     * @var array
     */
    protected $filterable_attributes = [
        'type', 'parent_id',
    ];

    /**
     * Attributes that can be filtered directly
     * using values from client without any logic.
     *
     * @var array
     */
    protected $filterable = [
        'type',
    ];

    /**
     * Attributes to be searched using like operator.
     *
     * @var array
     */
    protected $search_attributes = [
        'slug', 'title', 'description',
    ];

    protected $appends = [
        'title_en_ar', 'products_counter',
    ];

    // =========== Attributes Section ===========
    /**
     * Get constraint key based on table name
     * of current model
     * Override this method in the listable trait
     * to remove table name as it conflicts with
     * HasRecursiveRelationships trait.
     *
     * @param  string  $key
     * @return string
     */
    public function getConstraintKey(string $key): string
    {
        return $key;
    }
    public function registerMediaCollections(): void
    {
        // Keep the existing icon collection
        $this->addMediaCollection(config('taxonomies.icon_collection_name', 'taxonomy_icons'))
            ->singleFile();

        // Add the new photo collection
        $this->addMediaCollection(config('taxonomies.photo_collection_name', 'taxonomy_photos'))
            ->singleFile();
    }
    /**
     * Register media conversions
     * @return void
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $conversions = config('taxonomies.icon_conversions', ['thumb' => [120, 120]]);
        foreach ($conversions as $key => $value) {
            $this->addMediaConversion($key)
                ->fit(Manipulations::FIT_CROP, $value[0], $value[1])
                ->performOnCollections(config('taxonomies.icon_collection_name', 'taxonomy_icons'));
        }

        $this->addMediaConversion('small')
            ->fit(Manipulations::FIT_MAX, 300, 500)
            ->performOnCollections(config('taxonomies.photo_collection_name', 'taxonomy_photos'));

        $this->addMediaConversion('medium')
            ->fit(Manipulations::FIT_CROP, 600, 1000)
            ->performOnCollections(config('taxonomies.photo_collection_name', 'taxonomy_photos'));

        $this->addMediaConversion('large')
            ->fit(Manipulations::FIT_CROP, 900, 1500)
            ->performOnCollections(config('taxonomies.photo_collection_name', 'taxonomy_photos'));
    }


    public function getTitleEnArAttribute(): string
    {
        $title = json_decode($this->attributes['title'] ?? '');

        $en = optional($title)->en ?? 'N/A';
        $ar = optional($title)->ar ?? 'N/A';

        return "{$en} - {$ar}";
    }

    public function getProductsCounterAttribute(): string
    {
        return $this->products()->count();
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return parent::getSlugOptions()
            ->doNotGenerateSlugsOnUpdate();
    }

    // =========== Relations Section ===========
    public function parent(): BelongsTo
    {
        return $this->belongsTo('App\Models\Taxonomy', 'parent_id');
    }

    public function icon(): MorphOne
    {
        return $this->morphOne('App\Models\Media', 'model')
            ->where('collection_name', config('taxonomies.icon_collection_name'));
    }

    public function photo(): MorphOne
    {
        return $this->morphOne('App\Models\Media', 'model')
            ->where('collection_name', config('taxonomies.photo_collection_name', 'taxonomy_photos'));
    }

    public function attributes(): HasMany
    {
        return $this->hasMany('App\Models\Attribute', 'category_id');
    }

    public function appointmentForms(): BelongsToMany
    {
        return $this->belongsToMany(AppointmentForm::class)->withTimestamps();
    }

    public function appointmentFormTaxonomy(): HasMany
    {
        return $this->hasMany(AppointmentFormTaxonomy::class);
    }

    public function products()
    {
        return $this->morphedByMany(Product::class, 'taxable');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    // =========== Scopes Section ===========
    public function scopeParents(Builder $query): Builder
    {
        return $query->whereNull('taxonomies.parent_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('is_published', false);
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

        if ($request->has('parents')) {
            $query->isRoot();
        }

        if ($request->has('tree')) {
            $query->tree();
        }

        if ($request->has('is_appointment')) {
            $query->whereIsAppointment((bool) $request->get('is_appointment'));
        }

        return $query;
    }

    public function scopeWithListRelations(Builder $query, Request $request): Builder
    {
        $query->with('parent', 'icon', 'photo');

        return $query;
    }

    public function scopeWithListCounts(Builder $query, Request $request): Builder
    {
        return $query;
    }

    public function scopeWithApiListRelations(Builder $query, Request $request): Builder
    {
        return $query;
    }

    public function scopeWithSingleRelations(Builder $query): Builder
    {
        $query->with('parent', 'icon', 'photo');

        return $query;
    }
}
