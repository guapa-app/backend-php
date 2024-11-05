<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AppointmentFormTaxonomy extends Pivot
{
    protected $table = 'appointment_form_taxonomy';

    protected $fillable = [
        'appointment_form_id', 'taxonomy_id',
    ];

    public function appointmentForm(): BelongsTo
    {
        return $this->belongsTo(AppointmentForm::class);
    }

    public function taxonomy(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class);
    }
}
