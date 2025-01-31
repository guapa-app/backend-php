<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'vote_option_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function voteOption(): BelongsTo
    {
        return $this->belongsTo(VoteOption::class);
    }
}
