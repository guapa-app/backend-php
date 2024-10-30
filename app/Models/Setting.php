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

    public static function getSeviceExpiredAfter()
    {
        $record = static::firstOrCreate(['s_key' => 'service_expired_after'], [
            's_value'           => 60,
            's_unit'            => 'integer',
            's_validation_type' => 'number',
            's_validation'      => ['min'=> 0, 'max'=> 365],
            'instructions'      => 'Orders that have services (procedures) should expired after (numer) of days. if user does not use it before',
        ]);

        return (int) $record->s_value;
    }

    public static function getOrderReminderAfter()
    {
        $record = static::firstOrCreate(['s_key' => 'order_reminder_after'], [
            's_value'           => 12,
            's_unit'            => 'integer',
            's_validation_type' => 'number',
            's_validation'      => ['min'=> 1, 'max'=> 10000],
            'instructions'      => 'send reminder to user for bending orders after (number) of hours from order created',
        ]);

        return (int) $record->s_value;
    }

    public static function isAllMobileNumsAccepted()
    {
        $record = static::firstOrCreate(['s_key' => 'is_all_mob_nums_accepted'], [
            's_value'           => false,
            's_unit'            => 'bool',
            's_validation_type' => 'boolean',
            's_validation'      => [],
            'instructions'      => 'This setting used to accept all mobile numbers to text sms service in any environment',
        ]);

        return (int) $record->s_value;
    }

    public static function getMinSupportedVersion()
    {
        $record = static::firstOrCreate(['s_key' => 'min_supported_version'], [
            's_value' => 0,
            's_unit' => 'float',
            's_validation_type' => 'number',
            's_validation' => ['min' => 0, 'max' => 30],
            'instructions' => "0 to prevent version check, Please be careful when changing the app version. Ensure that any changes are aligned with the mobile team's requirements to avoid breaking the app. Forcing app updates can impact user experience, so proceed with caution.",
        ]);

        return (float)$record->s_value;
    }

    public function scopeByKey(Builder $query, string $key = ''): Builder
    {
        return $query->where('s_key', $key);
    }
}
