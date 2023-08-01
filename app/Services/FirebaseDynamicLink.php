<?php

namespace App\Services;

class FirebaseDynamicLink
{
    public static function create(string $link)
    {
        $url = 'https://firebasedynamiclinks.googleapis.com/v1/shortLinks?key=' . env('FIREBASE_API_KEY');

        $data = json_encode([
            "dynamicLinkInfo" => [
                "domainUriPrefix" => env('FIREBASE_DYNAMIC_LINKS_DEFAULT_DOMAIN'),
                "link" => $link,
            ],
            "suffix" => [
                "option" => "UNGUESSABLE",
            ],
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );

        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result, true);

        return $response['shortLink'] ?? $link;
    }
}
