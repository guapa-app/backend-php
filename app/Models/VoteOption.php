<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VoteOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'option',
        'votes_count'
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function userVotes(): HasMany
    {
        return $this->hasMany(UserVote::class);
    }
}
