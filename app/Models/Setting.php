<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        's_key',
        's_value',
        's_unit',
        's_validation',
        's_validation_type',
        'instructions',
    ];

    protected $casts = [
        's_validation' => 'array',
    ];

    public static function getTaxes()
    {
        $record = static::firstOrCreate(['s_key' => 'taxes'], [
            's_value'           => 15.00,
            's_unit'            => 'float',
            's_validation_type' => 'number',
            's_validation'      => ['min'=> 0, 'max'=> 100],
            'instructions'      => 'Taxes are a percentage of the service (example: 10% of 150 riyals = 15 riyals)',
        ]);

        return $record->s_value;
    }

    public static function getProductFees()
    {
        $record = static::firstOrCreate(['s_key' => 'product_fees'], [
            's_value'           => 0.00,
            's_unit'            => 'float',
            's_validation_type' => 'number',
            's_validation'      => ['min'=> 0, 'max'=> 100],
            'instructions'      => 'Fees are a percentage of the product (example: 10% of 150 riyals = 15 riyals)',
        ]);

        return $record->s_value;
    }

    public static function checkTestingMode()
    {
        $record = static::firstOrCreate(['s_key' => 'is_testing_mode_enabled'], [
            's_value'           => config('app.env') === 'production' ? false : true,
            's_unit'            => 'bool',
            's_validation_type' => 'boolean',
            's_validation'      => null,
            'instructions'      => 'This mode is enabled ONLY for testing environment',
        ]);

        return (bool) $record->s_value;
    }

    public static function getPaymentGatewayMethod()
    {
        $record = static::firstOrCreate(['s_key' => 'payment_gateway'], [
            's_value'           => 'ottu',
            's_unit'            => 'string',
            's_validation_type' => 'options',
            's_validation'      => ['moyasar', 'ottu'],
            'instructions'      => 'Available Payment Gateway To Use moyasar or ottu only. anything else \'ll get error.',
        ]);

        return (string) $record->s_value;
    }

    public static function getSmsService()
    {
        $record = static::firstOrCreate(['s_key' => 'sms_service'], [
            's_value'           => 'twilio',
            's_unit'            => 'string',
            's_validation_type' => 'options',
            's_validation'      => ['twilio', 'sinch'],
            'instructions'      => 'Available SMS Services To Use twilio or Sinch only. anything else \'ll get error.',
        ]);

        return (string) $record->s_value;
    }

    public function scopeByKey(Builder $query, string $key = ''): Builder
    {
        return $query->where('s_key', $key);
    }
}
