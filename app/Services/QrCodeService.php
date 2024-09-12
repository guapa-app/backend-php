<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    public function generate(array $data): string
    {
        $qrCodeString = "hash-id: ".$data['hash_id'];
        $qrCodeString .= " title: ".$data['title'];
        $qrCodeString .= " remain_amount: ".$data['amount_to_pay'];
        $qrCodeString .= " vendor_name: ".$data['vendor_name'];

        $file = QrCode::size(200)->generate(utf8_encode($qrCodeString));

        $path = '/public/qrcode/'.$data['hash_id'].'_'.rand(1000, 9999).'.svg';

        Storage::disk('s3')->put($path, $file);

        return $this->getGeneratedQrCode($path);
    }

    public function getGeneratedQrCode(string $path): string
    {
        return Storage::disk('s3')->url($path);
    }
}
