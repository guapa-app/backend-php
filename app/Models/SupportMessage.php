<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SupportMessage extends Model implements Listable
{
    use HasFactory, ListableTrait;

    /**
     * Attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
    	'subject', 'body', 'phone', 'read_at', 'user_id',
    ];

    /**
     * Attributes that can be filtered directly
     * using values from client without any logic
     * @var array
     */
    protected $filterable = [
        'user_id',
    ];

    /**
     * Attributes to be searched using like operator
     * @var array
     */
    protected $search_attributes = [
        'subject', 'body', 'phone',
    ];

    protected $appends = [
        'is_read',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function getIsReadAttribute()
    {
        return (bool) $this->read_at;
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function scopeApplyFilters(Builder $query, Request $request) : Builder
    {
        $filter = $request->get('filter');
        if (is_array($filter)) {
            $request = new Request($filter);
        }

        $query->searchLike($request);

        $query->applyDirectFilters($request);

        if ($request->has('read')) {
            $read = $request->get('read');
            $method = $read == '1' ? 'whereNotNull' : 'whereNull';
            $query->$method('read_at');
        }

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
