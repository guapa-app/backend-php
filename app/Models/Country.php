<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'currency_code',
        'phone_code',
        'tax_percentage',
        'active',
        'icon',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Define a relationship with the User model.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Define a relationship with the Vendor model.
     */
    public function vendors()
    {
        return $this->hasMany(Vendor::class);
    }

    /**
     * Define a relationship with the Product model.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Define a relationship with the Post model.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Define a relationship with the Order model.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
