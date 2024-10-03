<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WheelSpin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wheel_id',
        'spin_date',
        'points_awarded',
    ];

    protected $casts = [
        'spin_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wheel()
    {
        return $this->belongsTo(WheelOfFortune::class, 'wheel_id');
    }
}
