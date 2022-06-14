<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Contracts\Listable;
use App\Traits\Listable as ListableTrait;

class Page extends Model implements Listable
{
	use HasFactory, ListableTrait, HasTranslations, HasSlug;

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
     * using values from client without any logic
     * @var array
     */
    protected $filterable = [
        'published',
    ];

    /**
     * Attributes to be searched using like operator
     * @var array
     */
    protected $search_attributes = [
        'title', 'content',
    ];

    /**
     * Attributes to be added to each model
     * @var array
     */
    protected $appends = [
        'content_text',
    ];

    /**
     * Get the options for generating the slug.
     *
     * @return \Spatie\Sluggable\SlugOptions
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
        	->generateSlugsFrom('title')
        	->saveSlugsTo('slug')
        	->usingSeparator('-')
        	->usingLanguage('en');
    }

    /**
     * Get content without markup
     * @return object
     */
    public function getContentTextAttribute()
    {
        return (object) [
            'en' => $this->getTranslation('content', 'en'),
            'ar' => $this->getTranslation('content', 'ar'),
        ];
    }

    public function scopeApplyFilters(Builder $query, Request $request) : Builder
    {
        $filter = $request->get('filter');
        if (is_array($filter))
        {
            $request = new Request($filter);
        }

        $query->searchLike($request);

        $query->applyDirectFilters($request);
        
        return $query;
    }

    public function scopeWithListRelations(Builder $query, Request $request) : Builder
    {
        return $query;
    }

    public function scopeWithListCounts(Builder $query, Request $request) : Builder
    {
        return $query;
    }

    public function scopeWithSingleRelations(Builder $query) : Builder
    {
        return $query;
    }
}
