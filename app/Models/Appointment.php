<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
    	'vendor_id', 'from_time', 'to_time',
    ];

    public function vendor()
    {
    	return $this->belongsTo(Vendor::class);
    }
}
