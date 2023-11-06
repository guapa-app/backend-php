<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Comment extends Model implements Listable
{
    use HasFactory, ListableTrait;

    protected $fillable = [
        'post_id', 'user_id', 'user_type', 'content',
    ];

    protected $filterable = [
        'post_id', 'user_id', 'user_type',
    ];

    protected $search_attributes = [
        'content',
    ];

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = strip_tags($value);
    }

    public function user()
    {
        return $this->morphTo();
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function scopeApplyFilters(Builder $query, Request $request): Builder
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

    public function scopeWithListRelations(Builder $query, Request $request): Builder
    {
        $query->with('user');

        return $query;
    }

    public function scopeWithApiListRelations(Builder $query, Request $request): Builder
    {
        $query->with('user', 'user.photo');

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

    public function scopeCurrentVendor($query, $value)
    {
        return $query->whereHasMorph('user', [Vendor::class], function (Builder $query) use ($value) {
            $query->where('id', $value);
        });
    }
}
