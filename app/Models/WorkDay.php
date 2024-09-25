<?php

namespace App\Models;

use App\Enums\WorkDay as EnumsWorkDay;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function scopeCurrentVendor($query, $value): void
    {
        $query->where('vendor_id', '=', $value);
    }
}
