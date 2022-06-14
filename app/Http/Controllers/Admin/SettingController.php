<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\SettingRequest;
use App\Contracts\Repositories\SettingRepositoryInterface;

class SettingController extends BaseAdminController
{
    private $settingRepository;

    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        parent::__construct();
        
        $this->settingRepository = $settingRepository;
    }

    public function create(SettingRequest $request)
    {
    	$setting = $this->settingRepository->create($request->validated());
    	return response()->json($setting);
    }

    public function settings(Request $request, $id = 0)
    {
        $settings = $this->settingRepository->getAll($request);
    	return response()->json($settings);
    }

    public function update(SettingRequest $request)
    {
    	$data = $this->settingRepository->updateSettings($request->all());
    	return response()->json($data);
    }
}
