<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVendor extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $table = 'user_vendor';

    protected $fillable = [
        'user_id', 'vendor_id', 'role', 'email',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function scopeRole($query, $role): void
    {
        $query->where('role', $role);
    }

    public function scopeCurrentVendor($query, $value): void
    {
        $query->where('vendor_id', $value);
    }
}
