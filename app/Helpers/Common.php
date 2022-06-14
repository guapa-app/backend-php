<?php

namespace App\Helpers;

// use App\Repositories\Contracts\Eloquent\SettingRepositoryInterface;

/**
 * Common helper functions
 */
class Common
{
	/**
	 * Get setting value
	 * @param  string $settingKey
	 * @param  string $default
	 * @return string
	 */
	public static function getSetting(string $settingKey, $default = null)
	{
		$settingRepository = resolve(SettingRepositoryInterface::class);
		$settings = $settingRepository->getAll();

		if ( ! isset($settingKey))
		{
			return $default;
		}

		$setting = $settings->firstWhere('setting_key', $settingKey);
		if ( ! $setting)
		{
			return $default;
		}

		return $setting->setting_value;
	}

	/**
	 * Get all possible variations of phone number
	 * @param  string $phone
	 * @return array
	 */
	public static function getPhoneVariations(string $phone) : array
	{
		return [
			$phone,
			str_replace('+', '', $phone),
			'+' . $phone,
		];
	}

	/**
	 * Update env file value
	 * @param string $environmentKey
	 * @param string $configKey
	 * @param string $newValue
	 */
	public static function setEnv($environmentKey, $configKey, $newValue) : void
	{
	    file_put_contents(\App::environmentFilePath(), str_replace(
	        $environmentKey . '=' . config($configKey),
	        $environmentKey . '=' . $newValue,
	        file_get_contents(\App::environmentFilePath())
	    ));

	    config([$configKey => $newValue]);

	    // Reload the cached config       
	    if (file_exists(\App::getCachedConfigPath())) {
	        \Artisan::call("config:clear");
	        \Artisan::call("cache:clear");
	    }
	}
}
