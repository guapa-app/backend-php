<?php

namespace App\Contracts;

interface WhatsAppServiceInterface
{
    public function sendCampaign(array $entries);
}
