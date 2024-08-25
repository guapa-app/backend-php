<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShareLink;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShareLinkController extends Controller
{
    public function generatelink(Request $request)
    {
        $data = $this->validate($request, [
            'type' => 'required|string|in:product,vendor',
            'id'   => 'required|numeric',
        ]);

        // Validate the type and id
        $modelClass = $this->getModelClass($data['type']);
        $shareable = $modelClass::findOrFail($data['id']);

        // Generate unique identifier
        $identifier = Str::uuid();
        $link = url("/s/{$identifier}?ref={$data['type'][0]}&key={$data['id']}");

        // Store the link information
        $shareLink = ShareLink::query()->firstOrCreate(
            [
            'shareable_type' => $modelClass, // Store fully qualified class name
            'shareable_id' => $shareable->id, // Store shareable ID
        ],
            [
            'identifier' => $identifier,
            'link' => $link,
        ]
        );

        return $this->successJsonRes(['link' => $shareLink->link], __('api.success'));
    }

    public function redirectLink($identifier)
    {
        // Find the share link by identifier
        $shareLink = ShareLink::where('identifier', $identifier)->firstOrFail();

        // Redirect to the appropriate item in the app or store
        $itemUrl = $this->generateItemUrl($shareLink);

        // Redirect based on user agent
        return $this->handleRedirect($itemUrl, $shareLink);
    }

    private function getModelClass($type)
    {
        $modelClasses = [
            'vendor' => Vendor::class,
            'product' => Product::class,
        ];

        if (!isset($modelClasses[$type])) {
            abort(400, 'Invalid type');
        }

        return $modelClasses[$type];
    }

    private function generateItemUrl($shareLink)
    {
        $shareable = $shareLink->shareable;

        switch (get_class($shareable)) {
            case Product::class:
                return route('products.show', ['id' => $shareLink->shareable->id]);
            case Vendor::class:
                return route('vendors.show', ['id' => $shareLink->shareable->id]);
            default:
                abort(404);
        }
    }

    private function handleRedirect($itemUrl, $shareLink)
    {
        $userAgent = request()->header('User-Agent');

        // Check if the request comes from a mobile device
        if ($this->isMobile($userAgent)) {
            // Android
            $androidAppLink = "intent://{$shareLink->shareable_type}/{$shareLink->shareable_id}#Intent;scheme=https;package=com.guapanozom.app;end";
            $playStoreLink = 'https://play.google.com/store/apps/details?id=com.guapanozom.app'; //com.yourapp.package
            // iOS
            $iosAppLink = "guapa://{$shareLink->shareable_type}/{$shareLink->shareable_id}";
            $appStoreLink = 'https://apps.apple.com/sa/app/guapa/id1552554758'; //idYOUR_APP_ID

            return response()->view('redirect', [
                'iosAppLink' => $iosAppLink,
                'androidAppLink' => $androidAppLink,
                'appStoreLink' => $appStoreLink,
                'playStoreLink' => $playStoreLink,
                'webUrl' => $itemUrl,
            ]);
        }

        // If not mobile, redirect to the web URL
        return redirect(route('about-app'));
    }

    private function isMobile($userAgent)
    {
        return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $userAgent)
            || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v|v\-)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |\/)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt(\-|\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e(2|4)|e5|e\-|f\-|f2|f\-|f5|f7|f9|fs|g\-(s|t)|g50|g54|g6|g9|ga|gf|gg|gh|gi|g\-|g0|g1|g2|g3|g4|g5|g6|g7|g8|g9|gr|gs|gt|gv|i8|in|is|j8|ke|kg|ki|kt|kw|l3|l5|l6|l7|l8|lx|m3|m5|m6|m7|m8|m9|me|mi|mo|mt|mz|o5|o6|o7|o8|o9|op|ot|p1|p3|p5|p7|p8|p9|pi|pl|pt|px|s\-(5|7|8|d|g|l|n|p|r|s|t|v|x|y)|sa|sc|sd|sh|sl|sm|sp|sq|sr|st|sx|sz|t2|t5|t7|t8|tc|te|tg|th|tm|tq|tu|up|ur|ut|v5|v7|v8|v9|vm|vo|vx|wb|wt|x1|x3|x5|x7|xh|yo|yu|z1|z2|z3|z4|z5|z6|z7|z8|z9|zb|zg|zi|zp|zu|zz|1x|2\-)\/|[a-z0-9\-._~%!$&()*+,;=]*\/(index|main|default|welcome|home|landing|menu|start|launch|\.php|\.html|\.htm|\.cgi|\.asp|\.aspx|\.cfm|\.cfml|\.pl|\.py|\.rb|\.jsp|\.do|\.xml|\.html|\/|\/)$|^\/?$|^\/(\/+)?$/i', $userAgent);
    }
}
