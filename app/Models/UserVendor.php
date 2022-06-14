<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVendor extends Model
{
    use HasFactory;

    protected $primaryKey = null;
    public $incrementing = false;

    protected $table = 'user_vendor';

    protected $fillable = [
    	'user_id', 'vendor_id', 'role', 'email',
    ];

    public function vendor()
    {
    	return $this->belongsTo(Vendor::class);
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function isManager()
    {
    	return $this->role === 'manager';
    }

    public function scopeRole($query, $role)
    {
    	return $query->where('role', $role);
    }
}
