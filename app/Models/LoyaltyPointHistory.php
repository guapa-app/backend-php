<?php

namespace App\Models;

use App\Enums\LoyaltyPointAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoyaltyPointHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points',
        'action',
        'type',
        'sourceable_id',
        'sourceable_type'
    ];

    protected $appends = [
        'title',
        'points_change'
    ];

    /**
     * Get the user that owns the point history.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sourceable()
    {
        return $this->morphTo();
    }

    /**
     * Get a human-readable representation of the point change.
     *
     * @return string
     */
    public function getPointsChangeAttribute()
    {
        return $this->points > 0 ? '+' . $this->points : (string) $this->points;
    }

    /**
     * Get the formatted date for the transaction.
     *
     * @return string
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y'); // Format: 10 Oct 2024
    }

    /**
     * Title Attribute
     *
     * @return void
     */
    public function getTitleAttribute()
    {
        if ($this->action == LoyaltyPointAction::PURCHASE->value) {
            return __('Buy Product :product', [
                'product' => $this->sourceable->title,
            ]);
        } else if ($this->action == LoyaltyPointAction::RETURN_PURCHASE->value) {
            return __('Return Product :product', [
                'product' => $this->sourceable->title,
            ]);
        } elseif ($this->action == LoyaltyPointAction::SPIN_WHEEL->value) {
            return __('Spin Wheel - :wheel', [
                'wheel' => $this->sourceable->rarity_title,
            ]);
        } elseif ($this->action == LoyaltyPointAction::WALLET_CHARGING->value) {
            return __('Recharge balance - :package', [
                'package' => $this->sourceable->name,
            ]);
        } elseif ($this->action == LoyaltyPointAction::FRIENDS_REGISTRATIONS->value) {
            return __('Friends Registrations');
        } elseif ($this->action == LoyaltyPointAction::CONVERSION->value) {
            return __('Points conversion');
        }elseif ($this->action == LoyaltyPointAction::SYSTEM_ADDITION->value) {
            return __('System Addition');
        }elseif ($this->action == LoyaltyPointAction::SYSTEM_DEDUCTION->value) {
            return __('System Deduction');
        }


        return $this->action;
    }
}
