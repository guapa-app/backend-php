<?php

namespace App\Notifications;

use App\Channels\WhatsAppChannel;
use App\Models\MarketingCampaign;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CampaignNotification extends Notification
{
    use Queueable;

    protected $campaign;
    protected $type;

    public function __construct(MarketingCampaign $campaign)
    {
        $this->campaign = $campaign;
        $this->type = $this->determineCampaignType();
    }

    public function via($notifiable)
    {
        // This will allow us to easily add more channels in the future
        return $this->getChannels();
    }

    protected function getChannels()
    {
        $channels = [];

        if ($this->campaign->channel === 'whatsapp') {
            $channels[] = WhatsAppChannel::class;
        }
        // Future channel additions can be made here
        // if ($this->campaign->channel === 'email') {
        //     $channels[] = 'mail';
        // }

        return $channels;
    }

    public function toWhatsapp($notifiable)
    {
        return [
            'client' => $notifiable->phone,
            'campaignName' => $this->getWhatsAppCampaignName(),
            "campaignVersion" => $this->getWhatsAppCampaignVersion(),
            'variables' => $this->getWhatsAppVariables($notifiable)
        ];
    }

    protected function determineCampaignType()
    {
        $campaignable = $this->campaign->campaignable;

        if ($campaignable instanceof Offer) {
            return 'offer';
        } elseif ($campaignable instanceof Product) {
            return 'product';
        }

        throw new \Exception("Unsupported campaign type: " . get_class($campaignable));
    }

    protected function getWhatsAppCampaignName()
    {
        $campaignNames = [
            'offer' => 'offersprovidersservices',
            'product' => 'offersprovidersservices',
            // Add more campaign names for different types as needed
        ];

        return $campaignNames[$this->type];
    }

    protected function getWhatsAppVariables($notifiable)
    {
        $campaignable = $this->campaign->campaignable;
        $variables = [
            'username' => $notifiable->name,
            'image' => $this->getImage(),
        ];

        if ($this->type === 'offer') {
            $variables['discount'] = $campaignable->discount_string;
            $variables['title'] = $campaignable->product->title;
        } elseif ($this->type === 'product') {
            $variables['title'] = $campaignable->title;
//            $variables['price'] = $campaignable->price;
            $variables['discount'] = $campaignable->price;
        }

        return $variables;
    }

    protected function getWhatsAppCampaignVersion()
    {
        $campaignVersions = [
            'offer' => '01916c78-2738-877c-032a-6200d8561815',
            'product' => '01916c78-2738-877c-032a-6200d8561815',
            // Add more campaign versions for different types as needed
        ];

        return $campaignVersions[$this->type] ?? 'default-version';
    }

    protected function getImage()
    {
        $campaignable = $this->campaign->campaignable;
        return $campaignable->image?->url ?? ($this->type === 'offer' ? $campaignable->product->image?->url : null);
    }
}
