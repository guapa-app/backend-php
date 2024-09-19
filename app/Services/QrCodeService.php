<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    public function generate(array $data): string
    {
        $qrCodeString = "https://guapa?"
            . "hash_id={$data['hash_id']}"
            . "&client_name={$data['client_name']}"
            . "&client_phone={$data['client_phone']}"
            . "&vendor_name={$data['vendor_name']}"
            . "&paid_amount={$data['paid_amount']}"
            . "&remain_amount={$data['amount_to_pay']}"
            . "&title={$data['title']}"
            . "&item_price={$data['item_price']}"
            . "&item_price_after_discount={$data['item_price_after_discount']}"
            . "&item_image={$data['item_image']}";

        $file = QrCode::size(200)->generate(utf8_encode($qrCodeString));

        $path = '/qrcode/' . $data['hash_id'] . '_' . rand(1000, 9999) . '.svg';

        Storage::disk('s3')->put($path, $file);

        return $this->getGeneratedQrCode($path);
    }

    public function getGeneratedQrCode(string $path): string
    {
        return Storage::disk('s3')->url($path);
    }
}
