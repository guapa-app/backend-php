<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AppointmentFormValue extends Model
{
    protected $guarded = ['id'];

    public function appointmentForm()
    {
        return $this->belongsTo(AppointmentForm::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_appointments', 'value_id')
            ->withPivot('value', 'answer', 'appointment_form_id')
            ->withTimestamps();
    }
}
