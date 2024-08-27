<?php

namespace App\Observers;

use App\Models\Vendor;
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
        // Generate unique identifier
        $identifier = Str::uuid();
        $link = url("/s/{$identifier}?ref=v&key=$vendor->id");

        $vendor->shareLink()->createQuietly([
            // Generate unique identifier
            'identifier' => $identifier,
            'link' => $link,
        ]);
    }
}
