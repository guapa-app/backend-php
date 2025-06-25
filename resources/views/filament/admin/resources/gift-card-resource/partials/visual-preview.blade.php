<div class="mb-8">
    <!-- Admin Visual Preview -->
    <div class="gift-card-preview relative overflow-hidden rounded-lg shadow-lg mb-6"
         style="background: {{ $giftCard->background_color ?? '#FF8B85' }};
                background-image: {{ $giftCard->background_image ? 'url(' . $giftCard->background_image_url . ')' : 'none' }};
                background-size: cover;
                background-position: center;
                min-height: 300px;">
        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
        <div class="relative z-10 p-6 text-white">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-2xl font-bold">Gift Card</h2>
                    <p class="text-sm opacity-90">{{ $giftCard->gift_type_label }}</p>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold">{{ $giftCard->currency }} {{ number_format($giftCard->amount, 2) }}</div>
                    <div class="text-xs opacity-75">{{ $giftCard->currency }}</div>
                </div>
            </div>
            <div class="mb-4">
                <div class="text-xs opacity-75 mb-1">Code</div>
                <div class="font-mono text-lg font-bold tracking-wider">{{ $giftCard->code }}</div>
            </div>
            @if($giftCard->message)
            <div class="mb-4">
                <div class="text-sm opacity-90 italic">"{{ $giftCard->message }}"</div>
            </div>
            @endif
            <div class="absolute bottom-4 left-6 right-6">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs opacity-75">To:</div>
                        <div class="font-medium">{{ $giftCard->recipient_name }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs opacity-75">Status</div>
                        <div class="font-medium">
                            @if($giftCard->status === 'active')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                            @elseif($giftCard->status === 'used')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Used</span>
                            @elseif($giftCard->status === 'expired')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Expired</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">{{ ucfirst($giftCard->status) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @if($giftCard->expires_at)
                <div class="mt-2 text-xs opacity-75">
                    Expires: {{ $giftCard->expires_at->format('M d, Y') }}
                </div>
                @endif
            </div>
        </div>
    </div>
    <!-- User Visual Preview -->
    <h5 class="text-base font-medium text-gray-700 mb-2">User Preview</h5>
    @include('frontend.gift-cards._user-card', ['giftCard' => $giftCard])
</div>