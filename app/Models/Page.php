<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\Request;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Page extends Model implements Listable, HasMedia
{
    use HasFactory, ListableTrait, InteractsWithMedia, HasTranslations, HasSlug;

    protected $fillable = [
        'title', 'content', 'published',
    ];

    public $translatable = [
        'title', 'content',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'published' => 'boolean',
    ];

    /**
     * Attributes that can be filtered directly
     * using values from client without any logic.
     *
     * @var array
     */
    protected $filterable = [
        'published',
    ];

    /**
     * Attributes to be searched using like operator.
     *
     * @var array
     */
    protected $search_attributes = [
        'title', 'content',
    ];

    /**
     * Attributes to be added to each model.
     *
     * @var array
     */
    protected $appends = [
        'content_text',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('pages');
    }

    /**
     * Register media conversions.
     *
     * @return void
     */
//    public function registerMediaConversions(BaseMedia $media = null): void
//    {
//        $this->addMediaConversion('small')
//            ->fit(Manipulations::FIT_CROP, 200, 200)
//            ->performOnCollections('pages');
//
//        $this->addMediaConversion('medium')
//            ->fit(Manipulations::FIT_CROP, 300, 300)
//            ->performOnCollections('pages');
//
//        $this->addMediaConversion('large')
//            ->fit(Manipulations::FIT_MAX, 600, 600)
//            ->performOnCollections('pages');
//    }
    /**
     * Get the options for generating the slug.
     *
     * @return \Spatie\Sluggable\SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->usingSeparator('-')
            ->usingLanguage('en');
    }

    /**
     * Get content without markup.
     *
     * @return object
     */
    public function getContentTextAttribute()
    {
        return (object) [
            'en' => $this->getTranslation('content', 'en'),
            'ar' => $this->getTranslation('content', 'ar'),
        ];
    }

    public function image(): MorphOne
    {
        return $this->morphOne('App\Models\Media', 'model')
            ->where('collection_name', 'pages');
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

    public function scopeWithListRelations(Builder $query, Request $request): Builder
    {
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
