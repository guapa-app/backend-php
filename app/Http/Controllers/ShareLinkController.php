<?php

namespace App\Http\Controllers;

use App\Services\ShareLinkService;
use Illuminate\Http\Request;

class ShareLinkController extends Controller
{
    private $linkService;

    /**
     * @param $linkService
     */
    public function __construct(ShareLinkService $linkService)
    {
        $this->linkService = $linkService;
    }

    public function generatelink(Request $request)
    {
        $data = $this->validate($request, [
            'type' => 'required|string|in:product,vendor',
            'id' => 'required|numeric',
        ]);

        $link = $this->linkService->create($data);

        return $this->successJsonRes(['link' => $link], __('api.success'));
    }

    public function redirectLink($identifier)
    {
        list($shareLink, $itemUrl) = $this->linkService->redirectLink($identifier);

        // Redirect based on user agent
        return $this->linkService->handleRedirect($itemUrl, $shareLink);
    }
}
