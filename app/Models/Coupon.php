<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'discount_percentage',
        'discount_source',
        'expires_at',
        'max_uses',
        'single_user_usage',
        'admin_id',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'coupon_products');
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'coupon_vendors');
    }

    public function categories()
    {
        return $this->belongsToMany(Taxonomy::class, 'coupon_taxonomies', 'coupon_id', 'taxonomy_id');
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function isActive(): bool
    {
        if ($this->isExpired()) {
            return false;
        }

        if ($this->hasReachedMaxUses()) {
            return false;
        }

        return true;
    }

    private function isExpired()
    {
        return now()->gt($this->expires_at);
    }

    private function hasReachedMaxUses()
    {
        return $this->max_uses && $this->usages->sum('usage_count') >= $this->max_uses;
    }

    public function hasReachedMaxUsesForUser(User $user)
    {
        return $this->usages->where('user_id', $user->id)->sum('usage_count') >= $this->single_user_usage;
    }

    public function isCouponApplicableToProduct(Product $product): bool
    {
        $applicable = true;
        // Check vendors
        if ($this->vendors->isNotEmpty() && !$this->vendors->contains($product->vendor_id)) {
            $applicable = false;
        }
        // Check categories
        if ($applicable && $this->categories->isNotEmpty()) {
            $productCategories = $product->categories->pluck('id');
            if ($productCategories->intersect($this->categories->pluck('id'))->isEmpty()) {
                $applicable = false;
            }
        }
        // Check products
        if ($applicable && $this->products->isNotEmpty() && !$this->products->contains($product->id)) {
            $applicable = false;
        }

        return $applicable;
    }

    public function isCouponValid(Product $product, User $user): bool
    {
        return !$this->hasReachedMaxUsesForUser($user) &&
            $this->isCouponApplicableToProduct($product);
    }

    public function scopeCurrentVendor($query, $value)
    {
        $query->whereHas('vendors', function ($q) use ($value) {
            $q->where('vendor_id', $value);
        });
    }
}
