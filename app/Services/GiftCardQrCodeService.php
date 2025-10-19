<?php

namespace App\Services;

use App\Models\GiftCard;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GiftCardQrCodeService
{
    /**
     * Generate QR code for a gift card
     */
    public function generateForGiftCard(GiftCard $giftCard): string
    {
        $qrData = $this->buildQrCodeData($giftCard);
        $qrCodeString = $this->buildQrCodeString($qrData);

        return QrCode::size(300)
            ->format('png')
            ->margin(1)
            ->generate($qrCodeString);
    }

    /**
     * Build QR code data array for a gift card
     */
    public function buildQrCodeData(GiftCard $giftCard): array
    {
        return [
            'gift_card_id' => $giftCard->id,
            'code' => $giftCard->code,
            'amount' => $giftCard->amount,
            'currency' => $giftCard->currency,
            'total_amount' => $giftCard->total_amount,
            'status' => $giftCard->status,
            'payment_status' => $giftCard->payment_status,
            'gift_type' => $giftCard->gift_type,
            'expires_at' => $giftCard->expires_at?->toISOString(),
            'sender_name' => $giftCard->sender?->name,
            'recipient_name' => $giftCard->recipient_name ?: $giftCard->recipient?->name,
            'recipient_email' => $giftCard->recipient_email ?: $giftCard->recipient?->email,
            'recipient_phone' => $giftCard->recipient_number ?: $giftCard->recipient?->phone,
            'message' => $giftCard->message,
            'vendor_name' => $giftCard->vendor?->name,
            'product_name' => $giftCard->product?->name,
            'offer_title' => $giftCard->offer?->title,
            'redemption_url' => $this->buildRedemptionUrl($giftCard),
        ];
    }

    /**
     * Build QR code string from data array
     */
    public function buildQrCodeString(array $data): string
    {
        $qrData = [
            'type' => 'gift_card',
            'version' => '1.0',
            'data' => $data,
            'timestamp' => now()->toISOString(),
        ];

        return json_encode($qrData, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Build redemption URL for a gift card
     */
    public function buildRedemptionUrl(GiftCard $giftCard): string
    {
        $baseUrl = config('app.url');
        return "{$baseUrl}/gift-cards/redeem/{$giftCard->code}";
    }

    /**
     * Parse QR code data from string
     */
    public function parseQrCodeData(string $qrCodeString): ?array
    {
        try {
            $data = json_decode($qrCodeString, true);

            if (!$data || !isset($data['type']) || $data['type'] !== 'gift_card') {
                return null;
            }

            return $data['data'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Validate QR code data
     */
    public function validateQrCodeData(array $data): bool
    {
        $requiredFields = ['gift_card_id', 'code', 'amount', 'status', 'payment_status'];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Generate QR code with custom styling
     */
    public function generateStyledQrCode(GiftCard $giftCard, array $options = []): string
    {
        $defaultOptions = [
            'size' => 300,
            'format' => 'png',
            'margin' => 1,
            'color' => [0, 0, 0], // Black
            'backgroundColor' => [255, 255, 255], // White
        ];

        $options = array_merge($defaultOptions, $options);

        $qrData = $this->buildQrCodeData($giftCard);
        $qrCodeString = $this->buildQrCodeString($qrData);

        $qrCode = QrCode::size($options['size'])
            ->format($options['format'])
            ->margin($options['margin'])
            ->color($options['color'][0], $options['color'][1], $options['color'][2])
            ->backgroundColor($options['backgroundColor'][0], $options['backgroundColor'][1], $options['backgroundColor'][2]);

        return $qrCode->generate($qrCodeString);
    }

    /**
     * Generate QR code for sharing (with minimal data)
     */
    public function generateSharingQrCode(GiftCard $giftCard): string
    {
        $sharingData = [
            'code' => $giftCard->code,
            'amount' => $giftCard->amount,
            'currency' => $giftCard->currency,
            'redemption_url' => $this->buildRedemptionUrl($giftCard),
        ];

        $qrData = [
            'type' => 'gift_card_share',
            'version' => '1.0',
            'data' => $sharingData,
            'timestamp' => now()->toISOString(),
        ];

        $qrCodeString = json_encode($qrData, JSON_UNESCAPED_UNICODE);

        return QrCode::size(250)
            ->format('png')
            ->margin(1)
            ->generate($qrCodeString);
    }

    /**
     * Generate QR code for verification (with full data)
     */
    public function generateVerificationQrCode(GiftCard $giftCard): string
    {
        $verificationData = $this->buildQrCodeData($giftCard);
        $verificationData['verification_hash'] = $this->generateVerificationHash($giftCard);

        $qrData = [
            'type' => 'gift_card_verification',
            'version' => '1.0',
            'data' => $verificationData,
            'timestamp' => now()->toISOString(),
        ];

        $qrCodeString = json_encode($qrData, JSON_UNESCAPED_UNICODE);

        return QrCode::size(350)
            ->format('png')
            ->margin(1)
            ->generate($qrCodeString);
    }

    /**
     * Generate verification hash for gift card
     */
    private function generateVerificationHash(GiftCard $giftCard): string
    {
        $data = $giftCard->id . $giftCard->code . $giftCard->amount . $giftCard->status;
        return hash('sha256', $data . config('app.key'));
    }

    /**
     * Verify QR code hash
     */
    public function verifyQrCodeHash(GiftCard $giftCard, string $hash): bool
    {
        $expectedHash = $this->generateVerificationHash($giftCard);
        return hash_equals($expectedHash, $hash);
    }
}
