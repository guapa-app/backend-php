<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminUserPointHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_id',
        'action',
        'points',
        'reason',
    ];

    /**
     * The relationships to always load.
     *
     * @var array
     */
    protected $with = ['user', 'admin'];

    /**
     * Get the user associated with this transaction log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who performed this transaction.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
