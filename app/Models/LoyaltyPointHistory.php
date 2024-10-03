<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyPointHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points',
        'action',
        'type',
    ];

    /**
     * Get the user that owns the point history.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get a human-readable representation of the point change.
     *
     * @return string
     */
    public function getPointsChangeAttribute()
    {
        return $this->points > 0 ? '+' . $this->points : $this->points;
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
}
