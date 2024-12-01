<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\Translatable\HasTranslations;

class City extends Model implements Listable
{
    use ListableTrait, HasTranslations;

    protected $fillable = [
        'name',
    ];

    protected $translatable = [
        'name',
    ];

    /**
     * Attributes to be searched using like operator.
     * @var array
     */
    protected $search_attributes = [
        'name',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
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

    public function scopewithApiListRelations(Builder $query, Request $request): Builder
    {
        return $query;
    }

    public function scopeWithListCounts(Builder $query, Request $request): Builder
    {
        return $query;
    }

    public function scopeWithSingleRelations(Builder $query): Builder
    {
        return $query;
    }
}
