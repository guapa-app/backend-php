<?php

namespace App\Models;

use App\Contracts\Listable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\Translatable\HasTranslations;
use App\Traits\Listable as ListableTrait;

class Disease extends Model implements Listable
{
    use HasFactory, HasTranslations, ListableTrait;

    public $translatable = [
        'name',
    ];

    protected $fillable = [
        'name',
    ];

    protected $search_attributes = [
        'name',
    ];

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
