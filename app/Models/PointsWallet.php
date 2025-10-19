<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointsWallet extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'points'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loyaltyPointHistories()
    {
        return $this->morphMany(LoyaltyPointHistory::class, 'sourceable');
    }
}
