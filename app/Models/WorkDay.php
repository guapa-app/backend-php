<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkDay extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
    	'vendor_id', 'day',
    ];

    public function vendor()
    {
    	return $this->belongsTo(Vendor::class);
    }
}
