<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Enums\SupportMessageStatus;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SupportMessage extends Model implements Listable
{
    use HasFactory, ListableTrait;

    /**
     * Attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'parent_id', 'sender_type',
        'support_message_type_id', 'subject',
        'status', 'phone', 'body', 'read_at',
];

    /**
     * Attributes that can be filtered directly
     * using values from client without any logic.
     * @var array
     */
    protected $filterable = [
        'user_id',
    ];

    /**
     * Attributes to be searched using like operator.
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
        'status' => SupportMessageStatus::class,
    ];

    public function getIsReadAttribute()
    {
        return (bool) $this->read_at;
    }

    public function markAsResolved()
    {
        $this->update(['status' => SupportMessageStatus::Resolved]);
    }

    public function markAsInProgress()
    {
        $this->update(['status' => SupportMessageStatus::InProgress]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supportMessageType()
    {
        return $this->belongsTo(SupportMessageType::class);
    }

    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function scopeParents(Builder $query) : Builder
    {
        return $query->whereNull('parent_id');
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

    public function scopeWithApiListRelations(Builder $query, Request $request) : Builder
    {
        $query->with('supportMessageType', 'replies');

        return $query;
    }

    public function scopeWithListCounts(Builder $query, Request $request) : Builder
    {
        return $query;
    }

    public function scopeWithSingleRelations(Builder $query) : Builder
    {
        $query->with('supportMessageType', 'replies');

        return $query;
    }
}
