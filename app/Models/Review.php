<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Traits\Listable as ListableTrait;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory, ListableTrait;

    protected $fillable = [
    	'user_id', 'reviewable_id', 'reviewable_type',
    	'stars', 'comment',
    ];

    protected $filterable = [
    	'user_id', 'reviewable_id', 'reviewable_type', 'stars',
    ];

    protected $search_attributes = [
    	'comment',
    ];

    public function reviewable()
    {
    	return $this->morphTo();
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function scopeCurrentVendor($query, $value)
    {
        return $query->whereHasMorph('reviewable', [Product::class], function (Builder $query) use ($value) {
            $query->where('vendor_id', $value);
        })->orWhereHasMorph('reviewable', [Vendor::class], function (Builder $query) use ($value) {
            $query->where('id', $value);
        });
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

        return $query;
    }

    public function scopeWithListRelations(Builder $query, Request $request) : Builder
    {
    	$query->with('user', 'reviewable');
        return $query;
    }

    public function scopeWithApiListRelations(Builder $query, Request $request) : Builder
    {
    	$query->with('user');
        return $query;
    }

    public function scopeWithListCounts(Builder $query, Request $request) : Builder
    {
        return $query;
    }

    public function scopeWithSingleRelations(Builder $query) : Builder
    {
    	$query->with('user', 'reviewable');
        return $query;
    }
}
