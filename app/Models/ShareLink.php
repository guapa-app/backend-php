<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareLink extends Model
{
    use HasFactory;

    protected $fillable = ['identifier', 'shareable_id', 'shareable_type', 'link'];

    protected $filterable = [
        'shareable_id', 'shareable_type',
    ];

    public function shareable()
    {
        return $this->morphTo();
    }

    public function scopeVendor(Builder $query): Builder
    {
        return $query->where('shareable_type', 'vendor');
    }

    public function scopeProduct(Builder $query): Builder
    {
        return $query->where('shareable_type', 'product');
    }

    public function scopeOffer(Builder $query): Builder
    {
        return $query->where('shareable_type', 'offer');
    }
}
