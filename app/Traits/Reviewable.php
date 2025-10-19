<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Reviewable
{
    public function reviews(): MorphMany
    {
        return $this->morphMany('App\Models\Review', 'reviewable');
    }
}
