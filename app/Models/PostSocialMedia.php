<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PostSocialMedia extends Pivot
{
    public $timestamps = false;

    protected $table = 'post_social_media';

    protected $fillable = [
        'social_media_id', 'post_id', 'link',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function socialMedia()
    {
        return $this->belongsTo(SocialMedia::class);
    }

    public function scopeCurrentPost($query, $value)
    {
        return $query->where('post_id', $value);
    }
}
