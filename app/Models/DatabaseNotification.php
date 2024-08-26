<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Notifications\DatabaseNotification as BaseDatabaseNotification;

/**
 * @method self filter(string $type)
 */
class DatabaseNotification extends BaseDatabaseNotification
{
    public function scopeFilter(Builder $query, string $type): void
    {
        $query->when($type, fn(Builder $query) => $query->where('data->type', $type));
    }
}
