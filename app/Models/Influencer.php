<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Enums\InfluencerStatus;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class Influencer extends Model implements Listable
{
    use HasFactory, ListableTrait;

    protected $fillable = [
        'vendor_id', 'subject', 'body', 'status', 'read_at',
    ];

    protected $filterable = [
        'vendor_id',
    ];

    protected $search_attributes = [
        'subject', 'body', 'phone',
    ];

    protected $appends = [
        'is_read',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'status' => InfluencerStatus::class,
    ];

    public function getIsReadAttribute()
    {
        return (bool) $this->read_at;
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function scopeApplyFilters(Builder $builder, Request $request): Builder
    {
        return $builder;
    }

    public function scopeWithListRelations(Builder $builder, Request $request): Builder
    {
        return $builder;
    }

    public function scopeWithListCounts(Builder $builder, Request $request): Builder
    {
        return $builder;
    }

    public function scopeWithSingleRelations(Builder $builder): Builder
    {
        return $builder;
    }

    public function scopeWithApiListRelations(Builder $builder): Builder
    {
        return $builder;
    }
}
