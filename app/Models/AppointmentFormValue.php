<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AppointmentFormValue extends Model
{
    protected $guarded = ['id'];

    /**
     * @return BelongsTo
     */
    public function appointmentForm(): BelongsTo
    {
        return $this->belongsTo(AppointmentForm::class);
    }

    /**
     * @return BelongsToMany
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_appointments', 'value_id')
            ->withPivot('value', 'answer', 'appointment_form_id')
            ->withTimestamps();
    }
}
