<?php

namespace App\Services;

use App\Models\ShareLink;
use Illuminate\Support\Str;

class ShareLinkService
{
    public function create($data): string
    {
        // Generate unique identifier
        $identifier = Str::uuid();
        $link = url("/s/{$identifier}?ref={$data['type'][0]}&key={$data['id']}");

        // Store the link information
        $shareLink = ShareLink::query()
            ->firstOrCreate(
                [
                    'shareable_type' => $data,
                    'shareable_id' => $data['id'],
                ],
                [
                'identifier' => $identifier,
                'link' => $link,
            ]
            );

        return $shareLink->link;
    }
}
