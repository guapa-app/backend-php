<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShareLinkClick extends Model
{
    use HasFactory;

    protected $fillable = [
        'share_link_id',
        'ip_address',
        'user_agent',
        'referer',
        'platform'
    ];

    public function shareLink(): BelongsTo
    {
        return $this->belongsTo(ShareLink::class);
    }
}
