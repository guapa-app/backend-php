<?php

namespace App\Models;

use App\Enums\WorkDay as EnumsWorkDay;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkDay extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'vendor_id', 'day',
    ];

    protected $casts = [
        'day' => EnumsWorkDay::class,
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function scopeCurrentVendor($query, $value)
    {
        return $query->where('vendor_id', '=', $value);
    }
}
