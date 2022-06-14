<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasAddresses
{
	/**
	 * Get model addresses relationship
	 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
	 */
	public function addresses(): MorphMany
    {
        return $this->morphMany('App\Models\Address', 'addressable');
    }

    /**
	 * Get model single address relationship
	 * @return \Illuminate\Database\Eloquent\Relations\MorphOne
	 */
	public function address(): MorphOne
    {
        return $this->morphOne('App\Models\Address', 'addressable');
    }

    /**
     * Scope the query to return only models nearby a specific location by specific distance
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  double  $lat
     * @param  double  $lng
     * @param  integer $dist
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNearBy($query, $lat, $lng, $dist = 50)
    {
    	$table = $this->getTable();

        $query->select($table . '.*');

        if ( ! isset($dist) || ! is_numeric($dist) || (int) $dist < 1) {
            $dist = 50;
        }

        $distanceAggregate = "ROUND( (6371 * acos(cos(radians($lat)) * cos(radians(addresses.lat)) * cos(radians(addresses.lng) - radians($lng)) + sin(radians($lat)) * sin(radians(addresses.lat)))) , 1)";

        $query->addSelect([
        	'addresses.lat', 'addresses.lng', 'addresses.address_1',
        	\DB::raw($distanceAggregate . ' AS distance')
        ]);

        $query->join('addresses', function($join) use ($table) {
            $join->on('addresses.addressable_id', '=', $table . '.id');
            $join->where('addresses.addressable_type', '=', $this->getMorphClass());
        });
        
        $query->havingRaw("distance <= {$dist}");

        // Need to make sure no order by is provided elsewhere
        $query->orderBy('distance', 'asc');
        
        return $query;
    }
}
