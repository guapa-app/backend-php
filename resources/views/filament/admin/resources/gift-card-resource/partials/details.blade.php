<div class="space-y-4">
    <div>
        <span class="font-semibold">Code:</span>
        <span>{{ $giftCard->code }}</span>
    </div>
    <div>
        <span class="font-semibold">Amount:</span>
        <span>{{ number_format($giftCard->amount, 2) }}</span>
    </div>
    <div>
        <span class="font-semibold">Currency:</span>
        <span>{{ $giftCard->currency }}</span>
    </div>
    <div>
        <span class="font-semibold">Type:</span>
        <span>{{ $giftCard->gift_type_label }}</span>
    </div>
    <div>
        <span class="font-semibold">Status:</span>
        <span>{{ $giftCard->status_label }}</span>
    </div>
    <div>
        <span class="font-semibold">Recipient:</span>
        <span>{{ $giftCard->recipient_name }}</span>
    </div>
    <div>
        <span class="font-semibold">Message:</span>
        <span>{{ $giftCard->message }}</span>
    </div>
    <div>
        <span class="font-semibold">Expires At:</span>
        <span>{{ $giftCard->expires_at ? $giftCard->expires_at->format('Y-m-d H:i') : '-' }}</span>
    </div>
</div>