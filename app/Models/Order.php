<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Enums\OrderStatus;
use App\Enums\ProductType;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Order extends Model implements Listable
{
    use HasFactory, ListableTrait;

    protected $fillable = [
        'hash_id', 'user_id', 'vendor_id', 'address_id', 'total', 'status',
        'note', 'name', 'phone', 'invoice_url', 'cancellation_reason',
    ];

    /**
     * Attributes that can be filtered directly
     * using values from client without any logic.
     * @var array
     */
    protected $filterable = [
        'status', 'user_id', 'vendor_id',
    ];

    /**
     * Attributes to be searched using like operator.
     * @var array
     */
    protected $search_attributes = [
        'name', 'phone',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

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

    public function scopeCurrentVendor($query, $value)
    {
        return $query->where('vendor_id', $value);
    }

    public function scopeApplyFilters(Builder $query, Request $request): Builder
    {
        $filter = $request->get('filter');
        if (is_array($filter)) {
            $request = new Request($filter);
        }

        if ($request->has('user_id')) {
            $query->forUserWithFilteredItems();
        }

        $query->dateRange($request->get('startDate'), $request->get('endDate'));

        $query->searchLike($request);

        $query->applyDirectFilters($request);

        // Filter list orders based on the type of the items service or product
        if ($request->hasAny(['products', 'procedures'])) {
            $productType = $request->has('products') ? ProductType::Product : ProductType::Service;
            $query->hasProductType($productType);
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
        $query->with('vendor', 'user', 'address', 'items.product.offer', 'items.product.taxonomies', 'items.product.media');

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

    public function scopeStatus(Builder $query, $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeHasProductType(Builder $query, ProductType $type): Builder
    {
        return $query->whereHas('items.product', function (Builder $query) use ($type) {
            $query->where('type', $type);
        });
    }

    /**
     * Filter user orders to return all product orders
     * And all service orders except pending.
     * @param Builder $query
     * @return Builder
     */
    public function scopeForUserWithFilteredItems(Builder $query): Builder
    {
        return $query->distinct()
            ->select('orders.*')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where(function ($query) {
                $query->where('products.type', ProductType::Product)
                    ->orWhere(function ($query) {
                        $query->where('products.type', ProductType::Service)
                            ->where('orders.status', '!=', OrderStatus::Pending);
                    });
            });
    }
}
