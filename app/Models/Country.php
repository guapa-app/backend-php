<?php

namespace App\Models;

use App\Contracts\Listable;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Traits\Listable as ListableTrait;

class Country extends Model implements Listable
{
    use ListableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'currency_code',
        'phone_code',
        'phone_length',
        'tax_percentage',
        'active',
        'icon',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    protected $translatable = [
        'name',
    ];

    /**
     * Attributes to be searched using like operator.
     * @var array
     */
    protected $search_attributes = [
        'name',
    ];

    /**
     * Define a relationship with the User model.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Define a relationship with the Vendor model.
     */
    public function vendors()
    {
        return $this->hasMany(Vendor::class);
    }

    /**
     * Define a relationship with the Product model.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Define a relationship with the Post model.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Define a relationship with the Order model.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function scopeApplyFilters(Builder $query, Request $request): Builder
    {
        $filter = $request->get('filter');
        if (is_array($filter)) {
            $request = new Request($filter);
        }

        $query->searchLike($request);

        $query->applyDirectFilters($request);

        return $query;
    }

    public function scopeWithListRelations(Builder $query, Request $request): Builder
    {
        return $query;
    }

    public function scopewithApiListRelations(Builder $query, Request $request): Builder
    {
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
}
