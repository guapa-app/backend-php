<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Doctor extends Model implements Listable
{
    use HasFactory, ListableTrait;

    protected $fillable = [
    	'user_id', 'name', 'email', 'status',
    	'about', 'verified', 'phone',
    ];

    protected $casts = [
    	'verified' => 'boolean',
    ];

    /**
     * Attributes that can be filtered directly
     * using values from client without any logic
     * @var array
     */
    protected $filterable_attributes = [
        'status', 'verified',
    ];

    /**
     * Attributes to be searched using like operator
     * @var array
     */
    protected $search_attributes = [
        'name', 'email', 'phone',
    ];
}
