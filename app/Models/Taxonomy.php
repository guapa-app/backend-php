<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Traits\Listable as ListableTrait;
use Hamedov\Taxonomies\Taxonomy as BaseTaxonomy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\Sluggable\SlugOptions;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Taxonomy extends BaseTaxonomy implements Listable
{
    use ListableTrait, HasRecursiveRelationships;

    protected $fillable = [
      'fees',
    ];
    /**
     * Attributes that can be filtered directly
     * using values from client without any logic.
     * @var array
     */
    protected $filterable_attributes = [
        'type', 'parent_id',
    ];

    /**
     * Attributes that can be filtered directly
     * using values from client without any logic.
     * @var array
     */
    protected $filterable = [
        'type',
    ];

    /**
     * Attributes to be searched using like operator.
     * @var array
     */
    protected $search_attributes = [
        'slug', 'title', 'description',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return parent::getSlugOptions()
            ->doNotGenerateSlugsOnUpdate();
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Taxonomy', 'parent_id');
    }

    public function icon()
    {
        return $this->morphOne('App\Models\Media', 'model')
            ->where('collection_name', config('taxonomies.icon_collection_name'));
    }

    public function attributes()
    {
        return $this->hasMany('App\Models\Attribute', 'category_id');
    }

    public function scopeParents(Builder $query) : Builder
    {
        return $query->whereNull('taxonomies.parent_id');
    }

    public function scopeApplyFilters(Builder $query, Request $request) : Builder
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

        return $query;
    }

    public function scopeWithListRelations(Builder $query, Request $request) : Builder
    {
        $query->with('parent', 'icon');

        return $query;
    }

    public function scopeWithListCounts(Builder $query, Request $request) : Builder
    {
        return $query;
    }

    public function scopeWithApiListRelations(Builder $query, Request $request): Builder
    {
        return $query;
    }

    public function scopeWithSingleRelations(Builder $query) : Builder
    {
        $query->with('parent', 'icon');

        return $query;
    }

    /**
     * Get constraint key based on table name
     * of current model
     * Override this method in the listable trait
     * to remove table name as it conflicts with
     * HasRecursiveRelationships trait.
     * @param  string $key
     * @return string
     */
    public function getConstraintKey(string $key) : string
    {
        return $key;
    }
}
