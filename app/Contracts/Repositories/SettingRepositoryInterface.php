<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model;

interface SettingRepositoryInterface {

	/**
	 * Create new setting
	 * @param  array  $data
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function create(array $data): Model;

	/**
	 * Update all settings
	 * @param  array  $data
	 * @return array
	 */
	public function updateSettings(array $data): array;
}
