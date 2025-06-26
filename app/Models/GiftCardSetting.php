<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GiftCardSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type', // string, array, boolean, integer
        'description',
        'is_active',
    ];

    protected $casts = [
        'value' => 'json',
        'is_active' => 'boolean',
    ];

    // Setting keys
    public const SUGGESTED_AMOUNTS = 'suggested_amounts';
    public const BACKGROUND_COLORS = 'background_colors';
    public const DEFAULT_EXPIRATION_DAYS = 'default_expiration_days';
    public const MIN_AMOUNT = 'min_amount';
    public const MAX_AMOUNT = 'max_amount';
    public const DEFAULT_CURRENCY = 'default_currency';
    public const SUPPORTED_CURRENCIES = 'supported_currencies';
    public const CODE_LENGTH = 'code_length';
    public const CODE_PREFIX = 'code_prefix';
    public const MAX_FILE_SIZE = 'max_file_size';
    public const ALLOWED_FILE_TYPES = 'allowed_file_types';

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByKey($query, $key)
    {
        return $query->where('key', $key);
    }

    // Static methods for easy access
    public static function getValue($key, $default = null)
    {
        $setting = static::active()->byKey($key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setValue($key, $value, $type = 'string', $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description,
                'is_active' => true,
            ]
        );
    }

    // Convenience methods for common settings
    public static function getSuggestedAmounts()
    {
        $value = static::getValue(self::SUGGESTED_AMOUNTS, [50, 100, 200, 500, 1000]);

        // Ensure we return an array
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [$value];
        }

        return is_array($value) ? $value : [$value];
    }

    public static function getBackgroundColors()
    {
        $value = static::getValue(self::BACKGROUND_COLORS, [
            '#FF8B85', '#FFB3BA', '#FFD3B6', '#FFEFD1', '#DCEDC8',
            '#B2DFDB', '#B3E5FC', '#E1BEE7', '#F8BBD9', '#C8E6C9',
            '#BBDEFB', '#D1C4E9'
        ]);

        // Ensure we return an array
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [$value];
        }

        return is_array($value) ? $value : [$value];
    }

    public static function getDefaultExpirationDays()
    {
        return static::getValue(self::DEFAULT_EXPIRATION_DAYS, 365);
    }

    public static function getMinAmount()
    {
        return static::getValue(self::MIN_AMOUNT, 1);
    }

    public static function getMaxAmount()
    {
        return static::getValue(self::MAX_AMOUNT, 10000);
    }

    public static function getDefaultCurrency()
    {
        return static::getValue(self::DEFAULT_CURRENCY, 'SAR');
    }

    public static function getSupportedCurrencies()
    {
        $value = static::getValue(self::SUPPORTED_CURRENCIES, [
            'SAR' => 'Saudi Riyal',
            'USD' => 'US Dollar',
            'EUR' => 'Euro',
        ]);

        // Ensure we return an array
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [$value];
        }

        return is_array($value) ? $value : [$value];
    }

    public static function getCodeLength()
    {
        return static::getValue(self::CODE_LENGTH, 8);
    }

    public static function getCodePrefix()
    {
        return static::getValue(self::CODE_PREFIX, 'GC');
    }

    public static function getMaxFileSize()
    {
        return static::getValue(self::MAX_FILE_SIZE, 5242880); // 5MB
    }

    public static function getAllowedFileTypes()
    {
        $value = static::getValue(self::ALLOWED_FILE_TYPES, [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/svg+xml',
        ]);

        // Ensure we return an array
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [$value];
        }

        return is_array($value) ? $value : [$value];
    }

    /**
     * Get allowed file extensions for validation rules
     */
    public static function getAllowedFileExtensions()
    {
        $allowedTypes = static::getAllowedFileTypes();
        return array_map(function($type) {
            return explode('/', $type)[1];
        }, $allowedTypes);
    }

    /**
     * Validate if a file type is allowed
     */
    public static function isFileTypeAllowed($mimeType)
    {
        return in_array($mimeType, static::getAllowedFileTypes());
    }

    /**
     * Validate if a file size is within limits
     */
    public static function isFileSizeAllowed($fileSize)
    {
        return $fileSize <= static::getMaxFileSize();
    }

    // Initialize default settings
    public static function initializeDefaults()
    {
        $defaults = [
            [
                'key' => self::SUGGESTED_AMOUNTS,
                'value' => [50, 100, 200, 500, 1000],
                'type' => 'array',
                'description' => 'Suggested amounts for gift cards',
            ],
            [
                'key' => self::BACKGROUND_COLORS,
                'value' => [
                    '#FF8B85', '#FFB3BA', '#FFD3B6', '#FFEFD1', '#DCEDC8',
                    '#B2DFDB', '#B3E5FC', '#E1BEE7', '#F8BBD9', '#C8E6C9',
                    '#BBDEFB', '#D1C4E9'
                ],
                'type' => 'array',
                'description' => 'Available background colors for gift cards',
            ],
            [
                'key' => self::DEFAULT_EXPIRATION_DAYS,
                'value' => 365,
                'type' => 'integer',
                'description' => 'Default expiration period in days',
            ],
            [
                'key' => self::MIN_AMOUNT,
                'value' => 1,
                'type' => 'integer',
                'description' => 'Minimum gift card amount',
            ],
            [
                'key' => self::MAX_AMOUNT,
                'value' => 10000,
                'type' => 'integer',
                'description' => 'Maximum gift card amount',
            ],
            [
                'key' => self::DEFAULT_CURRENCY,
                'value' => 'SAR',
                'type' => 'string',
                'description' => 'Default currency for gift cards',
            ],
            [
                'key' => self::SUPPORTED_CURRENCIES,
                'value' => [
                    'SAR' => 'Saudi Riyal',
                    'USD' => 'US Dollar',
                    'EUR' => 'Euro',
                ],
                'type' => 'array',
                'description' => 'Supported currencies',
            ],
            [
                'key' => self::CODE_LENGTH,
                'value' => 8,
                'type' => 'integer',
                'description' => 'Length of gift card codes',
            ],
            [
                'key' => self::CODE_PREFIX,
                'value' => 'GC',
                'type' => 'string',
                'description' => 'Prefix for gift card codes',
            ],
            [
                'key' => self::MAX_FILE_SIZE,
                'value' => 5242880,
                'type' => 'integer',
                'description' => 'Maximum file size for background images (in bytes)',
            ],
            [
                'key' => self::ALLOWED_FILE_TYPES,
                'value' => [
                    'image/jpeg',
                    'image/png',
                    'image/gif',
                    'image/svg+xml',
                ],
                'type' => 'array',
                'description' => 'Allowed file types for background images',
            ],
        ];

        foreach ($defaults as $default) {
            static::updateOrCreate(
                ['key' => $default['key']],
                array_merge($default, ['is_active' => true])
            );
        }
    }
}
