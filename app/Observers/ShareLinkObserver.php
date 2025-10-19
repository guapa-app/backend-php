<?php

namespace App\Observers;

use App\Models\ShareLink;
use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

class ShareLinkObserver
{
    /**
     * Handle the ShareLink "creating" event.
     */
    public function creating(ShareLink $shareLink): void
    {
        Nova::whenServing(function (NovaRequest $request) use ($shareLink) {
            // Only invoked during Nova requests...
            $shareableType = ucfirst(preg_replace('/s$/', '', $request->get('shareable_type')));
            $identifier = Str::uuid();
            $link = url("/s/{$identifier}?ref={$request['shareable_type'][0]}&key={$request['shareable']}");

            // Check for existing record with the same name
            $existingRecord = ShareLink::query()
            ->where([
                'shareable_type' => $shareableType,
                'shareable_id' => $request['shareable'],
            ])->first();

            if ($existingRecord) {
                // Abort the creation process and redirect to the existing resource
                abort(redirect()->back());
            }

            $shareLink->fill([
                'identifier' => $identifier,
                'link' => $link,
            ]);
        });
    }
}
