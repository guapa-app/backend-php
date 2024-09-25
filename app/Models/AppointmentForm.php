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
        'type' => AppointmentTypeEnum::class
    ];

    /**
     * @return HasMany
     */
    public function values(): HasMany
    {
        return $this->hasMany(AppointmentFormValue::class);
    }

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

    /**
     * @return BelongsToMany
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_appointments')
            ->withPivot('key', 'answer', 'appointment_form_value_id')
            ->withTimestamps();
    }
}
