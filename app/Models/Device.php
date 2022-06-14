<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Device extends Model
{
	/**
	 * Mass assignable attributes
	 * 
	 * @var array
	 */
    protected $fillable = [
    	'user_id', 'user_type', 'guid', 'fcmtoken', 'type',
    ];

    /**
     * Get user for this device
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function user() : MorphTo
    {
    	return $this->morphTo('user');
    }
}
