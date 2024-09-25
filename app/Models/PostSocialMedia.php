<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostSocialMedia extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'post_id';

    protected $table = 'post_social_media';

    protected $fillable = [
        'social_media_id', 'post_id', 'link',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function socialMedia(): BelongsTo
    {
        return $this->belongsTo(SocialMedia::class);
    }

    public function scopeCurrentPost($query, $value): void
    {
        $query->where('post_id', $value);
    }
}
