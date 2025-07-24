<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ShareLink;
use App\Models\ShareLinkClick;
use App\Models\Vendor;
use Illuminate\Support\Str;

class ShareLinkService
{
    public function create($data): string
    {
        // Validate the type and id
        $modelClass = $this->getModelClass($data['type']);
        $modelClass::findOrFail($data['id']);

        // Generate short identifier using Str::random
        $identifier = $this->generateUniqueShortCode();

        // Create short link
//        $link = url("/s/{$identifier}");
        $link = config('app.url') . "/s/{$identifier}";
        // Store the link information
        $shareLink = ShareLink::query()->firstOrCreate(
            [
                'shareable_type' => $data['type'],
                'shareable_id' => $data['id'],
            ],
            [
                'identifier' => $identifier,
                'link' => $link,
            ]
        );

        return $shareLink->link;
    }

    public function handleRedirect($shareLink)
    {
        $userAgent = request()->header('User-Agent');

        // Check if the request comes from a mobile device
        if ($this->isMobile($userAgent)) {
            $key = $shareLink->shareable_id;
            // ref the first char of model name (v or p)
            $ref = strtolower(substr($shareLink->shareable_type, 0, 1));

            // Android
            $androidAppLink = "intent://{$shareLink->shareable_type}/{$shareLink->shareable_id}#Intent;scheme=https;package=com.guapanozom.app;end";
            $playStoreLink = 'https://play.google.com/store/apps/details?id=com.guapanozom.app'; //com.yourapp.package
            // iOS
            $iosAppLink = "guapa://share?ref={$ref}&key={$key}";
            $appStoreLink = 'https://apps.apple.com/sa/app/guapa/id1552554758'; //idYOUR_APP_ID

            return response()->view('redirect', [
                'iosAppLink' => $iosAppLink,
                'androidAppLink' => $androidAppLink,
                'appStoreLink' => $appStoreLink,
                'playStoreLink' => $playStoreLink,
                'webUrl' => config('app.url'),
            ]);
        }

        // If not mobile, redirect to the web URL
        return redirect(route('about-app'));
    }

    private function generateUniqueShortCode(): string
    {
        do {
            $code = Str::random(8);
        } while (ShareLink::where('identifier', $code)->exists());

        return $code;
    }

    public function getLinkByIdentifier($identifier)
    {
        // Find the share link by identifier
        $shareLink = ShareLink::where('identifier', $identifier)->firstOrFail();

        // Log the click
        $this->logClick($shareLink);

        return $shareLink;
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
        switch ($shareLink->shareable_type) {
            case 'product':
                return config('app.url') . "/products/{$shareLink->shareable_id}";
            case 'vendor':
                return config('app.url') . "/vendors/{$shareLink->shareable_id}";
            default:
                abort(404);
        }
    }

    private function isMobile($userAgent)
    {
        return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $userAgent)
            || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v|v\-)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |\/)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt(\-|\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e(2|4)|e5|e\-|f\-|f2|f\-|f5|f7|f9|fs|g\-(s|t)|g50|g54|g6|g9|ga|gf|gg|gh|gi|g\-|g0|g1|g2|g3|g4|g5|g6|g7|g8|g9|gr|gs|gt|gv|i8|in|is|j8|ke|kg|ki|kt|kw|l3|l5|l6|l7|l8|lx|m3|m5|m6|m7|m8|m9|me|mi|mo|mt|mz|o5|o6|o7|o8|o9|op|ot|p1|p3|p5|p7|p8|p9|pi|pl|pt|px|s\-(5|7|8|d|g|l|n|p|r|s|t|v|x|y)|sa|sc|sd|sh|sl|sm|sp|sq|sr|st|sx|sz|t2|t5|t7|t8|tc|te|tg|th|tm|tq|tu|up|ur|ut|v5|v7|v8|v9|vm|vo|vx|wb|wt|x1|x3|x5|x7|xh|yo|yu|z1|z2|z3|z4|z5|z6|z7|z8|z9|zb|zg|zi|zp|zu|zz|1x|2\-)\/|[a-z0-9\-._~%!$&()*+,;=]*\/(index|main|default|welcome|home|landing|menu|start|launch|\.php|\.html|\.htm|\.cgi|\.asp|\.aspx|\.cfm|\.cfml|\.pl|\.py|\.rb|\.jsp|\.do|\.xml|\.html|\/|\/)$|^\/?$|^\/(\/+)?$/i', $userAgent);
    }

    private function logClick(ShareLink $shareLink): void
    {
        ShareLinkClick::create([
            'share_link_id' => $shareLink->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referer' => request()->header('referer'),
            'platform' => $this->detectPlatform(request()->userAgent()),
        ]);
    }

    private function detectPlatform(?string $userAgent): string
    {
        $userAgent = strtolower($userAgent ?? '');

        if (str_contains($userAgent, 'android')) {
            return 'android';
        }

        if (str_contains($userAgent, 'iphone') || str_contains($userAgent, 'ipad')) {
            return 'ios';
        }

        return 'web';
    }

    /**
     * Update all existing share links to use short identifiers
     *
     * @return array Statistics about the migration
     */
    public function migrateToShortIdentifiers(): array
    {
        $stats = [
            'total' => 0,
            'updated' => 0,
            'failed' => 0,
            'skipped' => 0
        ];

        ShareLink::chunk(100, function ($shareLinks) use (&$stats) {
            foreach ($shareLinks as $shareLink) {
                $stats['total']++;

                // Skip if identifier is already short (less than or equal to 8 chars)
//                if (strlen($shareLink->identifier) <= 8) {
//                    $stats['skipped']++;
//                    continue;
//                }

                try {
                    // Generate new short identifier
                    $newIdentifier = $this->generateUniqueShortCode();

                    // Update the share link
                    $shareLink->update([
                        'identifier' => $newIdentifier,
                        'link' => config('app.url') . "/s/{$newIdentifier}",
                    ]);

                    $stats['updated']++;
                } catch (\Exception $e) {
                    \Log::error('Failed to update share link ID: ' . $shareLink->id, [
                        'error' => $e->getMessage(),
                        'shareLink' => $shareLink->toArray()
                    ]);
                    $stats['failed']++;
                }
            }
        });

        return $stats;
    }
}
