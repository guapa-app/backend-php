<?php

namespace App\Models\Scopes;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class CountryScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Check if the 'X-Country-ID' header exists and apply the scope
        $countryId = request()->header('X-Country-ID');
        if ($countryId and Schema::hasColumn($model->getTable(), 'country_id')) {
            $builder->where('country_id', $countryId);
        }
    }
}
