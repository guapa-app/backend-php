<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Address extends Model implements Listable
{
    use ListableTrait;

    const TYPES = [
        1 => 'Service center',
        2 => 'Sales outlet',
        3 => 'Service and sales',
        4 => 'Shipping',
        5 => 'Billing',
        6 => 'Primary',
        7 => 'Website',
    ];

    protected $fillable = [
        'addressable_id', 'addressable_type', 'title', 'city_id',
        'postal_code', 'lat', 'lng', 'address_1', 'address_2',
        'type', 'phone',
    ];

    protected $filterable = [
        'addressable_id', 'addressable_type', 'city_id', 'type',
    ];

    protected $search_attributes = [
        'title', 'address_1', 'address_2',
    ];

    public function addressable()
    {
        return $this->morphTo();
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    public function scopeType(Builder $query, int $type = 1): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeService(Builder $query): Builder
    {
        return $query->type(1);
    }

    public function scopeSales(Builder $query): Builder
    {
        return $query->type(2);
    }

    public function scopeServiceAndSales(Builder $query): Builder
    {
        return $query->type(3);
    }

    public function scopeVendor(Builder $query): Builder
    {
        return $query->whereIn('type', [1, 2, 3]);
    }

    public function scopeUser(Builder $query): Builder
    {
        return $query->whereIn('type', [4, 5, 6]);
    }

    public function scopeApplyFilters(Builder $query, Request $request): Builder
    {
        $filter = $request->get('filter');
        if (is_array($filter)) {
            $request = new Request($filter);
        }

        $query->searchLike($request);

        $query->applyDirectFilters($request);

        if ($request->has('vendor_id')) {
            $query->where('addressable_id', (int) $request->get('vendor_id'));
            $query->where('addressable_type', 'vendor');
        }

        if ($request->has('user_id')) {
            $query->where('addressable_id', (int) $request->get('user_id'));
            $query->where('addressable_type', 'user');
        }

        return $query;
    }

    public function scopeWithListRelations(Builder $query, Request $request): Builder
    {
        $query->with('addressable', 'city');

        return $query;
    }

    public function scopeWithApiListRelations(Builder $query, Request $request): Builder
    {
        $query->with('city');

        return $query;
    }

    public function scopeWithListCounts(Builder $query, Request $request): Builder
    {
        return $query;
    }

    public function scopeWithSingleRelations(Builder $query): Builder
    {
        $query->with('addressable', 'city');

        return $query;
    }

    public function scopeCurrentVendor($query, $value)
    {
        return $query->whereHasMorph('addressable', [Vendor::class], function (Builder $query) use ($value) {
            $query->where('id', $value);
        });
    }
}
