<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'channel',
        'audience_type',
        'audience_count',
        'message_cost',
        'taxes',
        'total_cost',
        'status',
        'invoice_url',
        'campaignable_id',
        'campaignable_type',
    ];

    const TYPES = [
        'product',
        'offer'
    ];
    const CHANNEL = [
        'whatsapp',
    ];


    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function campaignable()
    {
        return $this->morphTo();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'campaign_user');
    }

}
