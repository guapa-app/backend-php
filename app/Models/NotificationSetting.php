<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    protected $fillable = [
        'notification_module',
        'admin_id',
        'channels',
        'created_by_super_admin',
        'is_global',
    ];

    protected $casts = [
        'created_by_super_admin' => 'boolean',
        'is_global' => 'boolean',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
