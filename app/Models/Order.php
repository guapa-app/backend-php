<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Order extends Model implements Listable
{
    use HasFactory, ListableTrait;

    protected $fillable = [
    	'user_id', 'vendor_id', 'address_id', 'total', 'status',
    	'note', 'name', 'phone', 'is_used', 'invoice_url'
    ];

    /**
     * Attributes that can be filtered directly
     * using values from client without any logic
     * @var array
     */
    protected $filterable = [
        'status', 'user_id', 'vendor_id',
    ];

    /**
     * Attributes to be searched using like operator
     * @var array
     */
    protected $search_attributes = [
        'name', 'phone',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->is_used = false;
        });
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function address()
    {
    	return $this->belongsTo(Address::class);
    }

    public function items()
    {
    	return $this->hasMany(OrderItem::class);
    }

    public function invoice()
    {
    	return $this->hasOne(Invoice::class);
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

        if ($request->hasAny(['products', 'procedures'])) {
            $productType = $request->has('products') ? 'product' : 'service';
            $query->whereHas('items', function($q) use ($productType) {
                $q->whereHas('product', function($q2) use ($productType) {
                    $q2->where('type', $productType);
                });
            });
        }

        return $query;
    }

    public function scopeWithListRelations(Builder $query, Request $request): Builder
    {
        $query->with('user', 'vendor');
        return $query;
    }

    public function scopeWithApiListRelations(Builder $query, Request $request): Builder
    {
        $query->with('vendor', 'user', 'address', 'items.product.image');
        return $query;
    }

    public function scopeWithListCounts(Builder $query, Request $request): Builder
    {
        return $query;
    }

    public function scopeWithSingleRelations(Builder $query): Builder
    {
        $query->with('vendor', 'user', 'address', 'items', 'items.product.image', 'items.user');
        return $query;
    }
}
