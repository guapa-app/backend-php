<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\SettingRepositoryInterface;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Setting Repository.
 */
class SettingRepository extends EloquentRepository implements SettingRepositoryInterface
{
    /**
     * Items per page for pagination.
     * @var int
     */
    public $perPage = 10;

    /**
     * Construct an instance of the repo.
     * @param \App\Models\Setting $model
     */
    public function __construct(Setting $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all settings.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll() : Collection
    {
        return cache()->get('settings', function () {
            return $this->model->select([
                'id', 's_key', 's_value', 's_unit',
            ])->get();
        });
    }

    /**
     * Create new setting and persist in db.
     * @param  array  $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data) : Model
    {
        $data['s_value'] = strip_tags($data['s_value']);
        if (isset($data['instructions'])) {
            $data['instructions'] = strip_tags($data['instructions']);
        }

        $setting = parent::create($data);

        cache()->forget('settings');

        return $setting;
    }

    public function updateSettings(array $data) : array
    {
        $existing = Setting::pluck('s_key')->toArray();
        $data = array_filter($data, function ($key) use ($existing) {
            return in_array($key, $existing);
        }, ARRAY_FILTER_USE_KEY);

        foreach ($data as $key => $value) {
            $this->model->where('s_key', $key)->update([
                's_value' => strip_tags($value),
            ]);
        }

        cache()->forget('settings');

        $data['id'] = 0;

        return $data;
    }
}
