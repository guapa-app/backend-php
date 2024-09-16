<?php

namespace App\Models;

use App\Enums\AppointmentTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AppointmentForm extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'type' => AppointmentTypeEnum::class
    ];

    public function values()
    {
        return $this->hasMany(AppointmentFormValue::class);
    }

    public function taxonomies(): BelongsToMany
    {
        return $this->belongsToMany(Taxonomy::class);
    }

    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class)->withTimestamps();
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_appointments')
            ->withPivot('value', 'answers', 'value_id')
            ->withTimestamps();
    }
}
