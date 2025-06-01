<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'points_required',
        'value',
        'max_uses_per_user',
        'total_available',
        'used_count',
        'status',
        'expires_at',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'expires_at' => 'datetime',
    ];

    public const TYPE_COUPON = 'coupon';
    public const TYPE_GIFT_CARD = 'gift_card';
    public const TYPE_SHIPPING_DISCOUNT = 'shipping_discount';
    public const TYPE_PRODUCT_DISCOUNT = 'product_discount';
    public const TYPE_CASH_CREDIT = 'cash_credit';

    public function exchangeTransactions()
    {
        return $this->hasMany(ExchangeTransaction::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function scopeAvailable($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('total_available')
                    ->orWhereRaw('used_count < total_available');
            });
    }

    public function isAvailable(): bool
    {
        return $this->status === 'active' &&
            ($this->expires_at === null || $this->expires_at > now()) &&
            ($this->total_available === null || $this->used_count < $this->total_available);
    }

    public function canBeUsedByUser(int $userId): bool
    {
        if (!$this->isAvailable()) {
            return false;
        }

        if ($this->max_uses_per_user === null) {
            return true;
        }

        $userUsageCount = $this->exchangeTransactions()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        return $userUsageCount < $this->max_uses_per_user;
    }
}
