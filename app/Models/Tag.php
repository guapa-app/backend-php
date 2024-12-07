<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Tag extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = [
        'title',
    ];

    public $fillable = [
        'title',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
