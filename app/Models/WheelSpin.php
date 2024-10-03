<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WheelSpin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wheel_id',
        'spin_date',
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
