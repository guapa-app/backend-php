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

    public function redirectLink(Request $request, $identifier)
    {

        $shareLink = $this->linkService->getLinkByIdentifier($identifier);
        $ref = $request->get('ref');
        $key = $request->get('key');
        if (empty($ref) && empty($key)) {
            $key = $shareLink->shareable_id;
            // ref the first char of model name (v or p)
            $ref = strtolower(substr($shareLink->shareable_type, 0, 1));
            return redirect("https://guapa.com.sa/s/{$identifier}?ref={$ref}&key={$key}");
        }

        $this->linkService->logShareLinkClicked($shareLink);

        // Redirect based on user agent
        return $this->linkService->handleRedirect($shareLink);
    }
}
