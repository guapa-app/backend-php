<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'country_id',
        'fee_option',
        'fee_percentage',
        'fee_fixed',
    ];

    public function category()
    {
        return $this->belongsTo(Taxonomy::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
