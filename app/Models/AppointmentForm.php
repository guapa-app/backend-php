<?php

namespace App\Models;

use App\Enums\AppointmentTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppointmentForm extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'type' => AppointmentTypeEnum::class,
        'options' => 'json'
    ];

    /**
     * @return BelongsToMany
     */
    public function taxonomies(): BelongsToMany
    {
        return $this->belongsToMany(Taxonomy::class);
    }

    /**
     * @return BelongsToMany
     */
    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class)->withTimestamps();
    }
}
