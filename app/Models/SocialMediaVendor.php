<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMediaVendor extends Model
{
    protected $primaryKey = 'vendor_id';
    public $incrementing = false;

    protected $table = 'social_media_vendor';

    protected $fillable = [
        'social_media_id', 'vendor_id', 'link'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function socialMedia()
    {
        return $this->belongsTo(SocialMedia::class);
    }

    public function scopeCurrentVendor($query, $value)
    {
        return $query->where('vendor_id', $value);
    }
}
