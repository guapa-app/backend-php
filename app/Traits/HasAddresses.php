<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\DB;

trait HasAddresses
{
    /**
     * Get model addresses relationship.
     * @return MorphMany
     */
    public function addresses(): MorphMany
    {
        return $this->morphMany('App\Models\Address', 'addressable');
    }

    /**
     * Get model single address relationship.
     * @return MorphOne
     */
    public function address(): MorphOne
    {
        return $this->morphOne('App\Models\Address', 'addressable');
    }

    /**
     * Scope the query to return only models nearby a specific location by specific distance.
     * @param Builder $query
     * @param float $lat
     * @param float $lng
     * @param int $dist
     * @return Builder
     */
    public function scopeNearBy($query, $lat, $lng, $dist = 50)
    {
        $table = $this->getTable();

        $query->select($table . '.*');

        if (!isset($dist) || !is_numeric($dist) || (int) $dist < 1) {
            $dist = 50;
        }

        $distanceAggregate = "ROUND( (6371 * acos(cos(radians($lat)) * cos(radians(addresses.lat)) * cos(radians(addresses.lng) - radians($lng)) + sin(radians($lat)) * sin(radians(addresses.lat)))) , 1)";

        $query->addSelect([
            'addresses.lat', 'addresses.lng', 'addresses.address_1',
            DB::raw($distanceAggregate . ' AS distance'),
        ]);

        $query->join('addresses', function ($join) use ($table) {
            $join->on('addresses.addressable_id', '=', $table . '.id');
            $join->where('addresses.addressable_type', '=', $this->getMorphClass());
        });

        $query->havingRaw("distance <= {$dist}");

        // Need to make sure no order by is provided elsewhere
        $query->orderBy('distance', 'asc');

        return $query;
    }
    /**
     * Scope the query to return only models that have an address with a specific city ID.
     * @param Builder $query
     * @param int $cityId
     * @return Builder
     */
    public function scopeHasCity(Builder $query, int $cityId): Builder
    {
        return $query->whereHas('addresses', function ($q) use ($cityId) {
            $q->where('city_id', $cityId)
                ->where('addressable_type', $this->getMorphClass());
        });
    }

}
