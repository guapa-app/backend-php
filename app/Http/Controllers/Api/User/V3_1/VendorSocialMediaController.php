<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\V3\VendorSocialMediaRequest;
use App\Models\SocialMedia;
use App\Models\Vendor;
use App\Services\VendorService;

class VendorSocialMediaController extends BaseApiController
{
    protected $vendorService;

    public function __construct(VendorService $vendorService)
    {
        parent::__construct();

        $this->vendorService = $vendorService;
    }

    public function store(Vendor $vendor, VendorSocialMediaRequest $request)
    {
        $data = $request->validated();

        $this->vendorService->addSocialMedia($vendor, $data);

        return $this->successJsonRes(message: __('success'));
    }

    public function update(Vendor $vendor, SocialMedia $socialMedium, VendorSocialMediaRequest $request)
    {
        $data = $request->validated();

        $this->vendorService->updateSocialMedia($vendor, $socialMedium->id, $data);

        return $this->successJsonRes(message: __('success'));
    }

    public function destroy(Vendor $vendor, SocialMedia $socialMedium)
    {
        $this->vendorService->deleteSocialMedia($vendor, $socialMedium->id);

        return $this->successJsonRes(message: __('success'));
    }
}
