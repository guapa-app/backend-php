<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exchange_reward_id',
        'points_used',
        'status',
        'exchange_data',
        'expires_at',
        'redeemed_at'
    ];

    protected $casts = [
        'exchange_data' => 'array',
        'expires_at' => 'datetime',
        'redeemed_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_EXPIRED = 'expired';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exchangeReward()
    {
        return $this->belongsTo(ExchangeReward::class);
    }

    public function loyaltyPointHistories()
    {
        return $this->morphMany(LoyaltyPointHistory::class, 'sourceable');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_COMPLETED]);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at < now();
    }

    public function markAsRedeemed()
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'redeemed_at' => now()
        ]);
    }
}
