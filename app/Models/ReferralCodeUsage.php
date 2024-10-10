<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralCodeUsage extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'invitee_id'];

    public function inviter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function invitee()
    {
        return $this->belongsTo(User::class, 'invitee_id');
    }
}
