<?php

namespace App\Helpers;

use App;
use App\Contracts\Repositories\SettingRepositoryInterface;
use Artisan;
use Illuminate\Support\Facades\Log;

/**
 * Common helper functions.
 */
class Common
{
    /**
     * Get setting value.
     * @param string $settingKey
     * @param string $default
     * @return string
     */
    public static function getSetting(string $settingKey, $default = null)
    {
        $settingRepository = resolve(SettingRepositoryInterface::class);
        $settings = $settingRepository->getAll();

        if (!isset($settingKey)) {
            return $default;
        }

        $setting = $settings->firstWhere('s_key', $settingKey);
        if (!$setting) {
            return $default;
        }

        return $setting->s_value;
    }

    /**
     * Get all possible variations of phone number.
     * @param string $phone
     * @return array
     */
    public static function getPhoneVariations(string $phone): array
    {
        return [
            $phone,
            str_replace('+', '', $phone),
            '+' . $phone,
        ];
    }

    /**
     * Remove leading "+" or "00" or "+00" or "00+"
     * and remove "0" after the country code if it exists.
     * @param string $phone
     * @return string
     */
    public static function removeZeroFromPhoneNumber(string $phone): string
    {
        return preg_replace('/^(\+|00|\+00|00\+)?9660?/', '966', $phone);
    }

    /**
     * Remove leading "+" or "00" or "+00" or "00+"
     * @param string $phone
     * @return string
     */
    public static function removePlusFromPhoneNumber(string $phone): string
    {
        return preg_replace('/^(\+00|\+0|\+|00\+)/', '', $phone);
    }

    /**
     * Rules to validate requiested phone number.
     * @return string
     */
    public static function phoneValidation(): string
    {
        return 'numeric|regex:/^9665[0-9]{8}$/';
    }

    /**
     * Advanced Saudi mobile number validation with multiple format support.
     * Validates Saudi mobile numbers in various formats:
     * - 9665xxxxxxxx (international format)
     * - +9665xxxxxxxx (with plus)
     * - 05xxxxxxxx (local format)
     * - 5xxxxxxxx (without leading zero)
     *
     * @param string $phone
     * @return bool
     */
    public static function validateSaudiMobileNumber(string $phone): bool
    {
        // Remove all non-digit characters except +
        $cleanPhone = preg_replace('/[^\d+]/', '', $phone);

        // Patterns for different Saudi mobile number formats
        $patterns = [
            '/^9665[0-9]{8}$/',           // 9665xxxxxxxx
            '/^\+9665[0-9]{8}$/',         // +9665xxxxxxxx
            '/^05[0-9]{8}$/',             // 05xxxxxxxx
            '/^5[0-9]{8}$/',              // 5xxxxxxxx
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $cleanPhone)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Normalize Saudi mobile number to international format (9665xxxxxxxx).
     *
     * @param string $phone
     * @return string|null Returns null if invalid format
     */
    public static function normalizeSaudiMobileNumber(string $phone): ?string
    {
        // Remove all non-digit characters except +
        $cleanPhone = preg_replace('/[^\d+]/', '', $phone);

        // Handle different formats
        if (preg_match('/^9665[0-9]{8}$/', $cleanPhone)) {
            return $cleanPhone; // Already in correct format
        }

        if (preg_match('/^\+9665[0-9]{8}$/', $cleanPhone)) {
            return substr($cleanPhone, 1); // Remove + and return
        }

        if (preg_match('/^05[0-9]{8}$/', $cleanPhone)) {
            return '966' . substr($cleanPhone, 1); // Remove 0, add 966
        }

        if (preg_match('/^5[0-9]{8}$/', $cleanPhone)) {
            return '966' . $cleanPhone; // Add 966 prefix
        }

        return null; // Invalid format
    }

    /**
     * Update env file value.
     * @param string $environmentKey
     * @param string $configKey
     * @param string $newValue
     */
    public static function setEnv($environmentKey, $configKey, $newValue): void
    {
        file_put_contents(App::environmentFilePath(), str_replace(
            $environmentKey . '=' . config($configKey),
            $environmentKey . '=' . $newValue,
            file_get_contents(App::environmentFilePath())
        ));

        config([$configKey => $newValue]);

        // Reload the cached config
        if (file_exists(App::getCachedConfigPath())) {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
        }
    }

    public static function getLocalizedUnitString($value, $unit)
    {
        $key = self::getLocalizationKey($value, $unit);
        $countString = self::getCountString($value);

        return __($key, ['count' => $countString]);
    }

    private static function getLocalizationKey($value, $unit)
    {
        if ($value == 0) {
            return __('api.no_' . $unit . 's');
        } elseif ($value == 1) {
            return __('api.one_' . $unit);
        } else {
            return __('api.x_' . $unit . 's');
        }
    }

    private static function getCountString($value)
    {
        if ($value == 0) {
            return __('api.no');
        } else {
            return $value;
        }
    }

    public static function mapIdName(array $data): array
    {
        return array_map(function ($v, $k) {
            return ['id' => $k, 'name' => $v];
        }, $data, array_keys($data));
    }

    /**
     * @param string $model
     * @param int $length
     * @return bool|string
     */
    public static function generateUniqueHashForModel(string $model, int $length = 20, bool $numeric = false)
    {
        // Generate a random string of characters.
        $hash = $numeric ? rand(0, $length) : str_random($length);

        // Check if the hash already exists in the database.
        // If the hash exists, generate a new one.
        if (app($model)->where('hash_id', $hash)->first()) {
            $hash = self::generateUniqueHashForModel($model, $length);
        }

        // Return the unique hash ID.
        return $hash;
    }

    public static function logReq($message = '', $data = null)
    {
        Log::alert(
            '***' .
                "\nMessage >-> $message" .
                "\nReq method >-> " . request()->method() .
                "\nPath >-> " . request()->decodedPath() .
                "\nRoute name >-> " . request()->route()?->getName() .
                "\n***",
            [
                "\nRequest Data >-> " => request()->all(),
                "\nData >-> " => $data,
            ]
        );
    }
}
