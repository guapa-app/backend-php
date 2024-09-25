<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class SupportMessageType extends Model implements Listable
{
    use HasFactory, ListableTrait;

    protected $fillable = ['name'];

    public function supportMessage(): HasMany
    {
        return $this->hasMany(SupportMessage::class);
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
}
