<?php

namespace App\Services;

use App\Contracts\WhatsAppServiceInterface;
use GuzzleHttp\Client;

class ConnectlyWhatsAppService implements WhatsAppServiceInterface
{
    protected $client;
    protected $businessId;

    public function __construct()
    {
        $this->businessId = config('services.connectly.business_id');
        $this->client = new Client([
            'base_uri' => config('services.connectly.url'),
            'headers' => [
                'x-api-key' => config('services.connectly.key'),
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function sendCampaign(array $entries)
    {
        \Log::info('Sending WhatsApp campaign:', $entries);
        $formattedEntries = array_map(function ($entry) {
            return [
                'client' => $entry['client'],
                'campaignName' => $entry['campaignName'],
                'variables' => $entry['variables'],
            ];
        }, $entries);

        $payload = ['entries' => $formattedEntries];

        \Log::info('Payload being sent to Connectly:', $payload);

        try {
            $response = $this->client->post("v1/businesses/{$this->businessId}/send/campaigns", [
                'json' => $payload,
            ]);
            $result = json_decode($response->getBody(), true);
            \Log::info('WhatsApp campaign sent:', $result);

            return $result;
        } catch (\Exception $e) {
            \Log::error('Failed to send WhatsApp campaign: ' . $e->getMessage());

            return null;
        }
    }
}
