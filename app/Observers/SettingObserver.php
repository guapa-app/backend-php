<?php

namespace App\Observers;

use App\Models\Setting;

class SettingObserver
{
    /**
     * Handle the Setting "updating" event.
     */
    public function updating(Setting $setting): bool
    {
        if ($setting->isDirty('s_key')) {
            return false;
        }

        if ($setting->s_validation_type == 'boolean') {
            return in_array($setting->s_value, ['true', '1', 1, 'false', '0', 0]) ?: false;
        }

        if ($setting->s_validation_type == 'number') {
            return $setting->s_value >= $setting->s_validation['min'] &&
                $setting->s_value < $setting->s_validation['max'];
        }

        if ($setting->s_validation_type == 'options') {
            return in_array($setting->s_value, $setting->s_validation) ?: false;
        }
    }

    /**
     * Handle the Setting "deleting" event.
     */
    public function deleting(Setting $setting): bool
    {
        return false;
    }
}
