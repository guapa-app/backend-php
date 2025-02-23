<?php

namespace App\Observers;

use App\Models\Vendor;
use App\Services\ShareLinkService;
use Illuminate\Support\Str;

class VendorObserver
{
    /**
     * Handle the Vendor "created" event.
     *
     * @param Vendor $vendor
     * @return void
     */
    public function created(Vendor $vendor)
    {
        $data = [
            'type' => 'vendor',
            'id' => $vendor->id,
        ];

        $shareLinkService = new ShareLinkService();
        $shareLinkService->create($data);
    }
}
