<?php

namespace App\Helpers;

use App;
use App\Contracts\Repositories\SettingRepositoryInterface;
use Artisan;

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

        $setting = $settings->firstWhere('setting_key', $settingKey);
        if (!$setting) {
            return $default;
        }

        return $setting->setting_value;
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

    public static function mapIdName(array $data) :array
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
    public static function generateUniqueHashForModel(string $model, int $length = 20)
    {
        // Generate a random string of characters.
        $hash = str_random($length);

        // Check if the hash already exists in the database.
        // If the hash exists, generate a new one.
        if (app($model)->where('hash_id', $hash)->first()) {
            $hash = self::generateUniqueHashForModel($model, $length);
        }

        // Return the unique hash ID.
        return $hash;
    }
}
