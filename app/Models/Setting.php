<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_key',
        'setting_value',
        'setting_unit',
        'instructions',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($setting) {
            if ($setting->isDirty('setting_key')) return false;
        });

        static::deleting(function ($setting) {
            return false;
        });
    }

    public static function getTaxes()
    {
        $record = static::firstOrCreate(['setting_key' => "taxes"], [
            "setting_value" => 0.00,
            "instructions" => "Taxes are a percentage of the service (example: 10% of 150 riyals = 15 riyals)",
        ]);
        return $record->setting_value;
    }

    public static function getProductFees()
    {
        $record = static::firstOrCreate(['setting_key' => "product_fees"], [
            "setting_value" => 0.00,
            "instructions" => "Fees are a percentage of the product (example: 10% of 150 riyals = 15 riyals)",
        ]);
        return $record->setting_value;
    }
    public static function checkTestingMode()
    {
        $record = static::firstOrCreate(['setting_key' => "is_testing_mode_enabled"], [
            "setting_value" => config('app.env') === 'production' ? false : true,
            "setting_unit" => 'bool',
            "instructions" => "this mode is enabled ONLY for testing environment",
        ]);
        return (bool)$record->setting_value;
    }
}
