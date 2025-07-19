@php
    $backgroundStyle = '';
    $hasImage = false;
    if ($giftCard->background_image_id && $giftCard->backgroundImage) {
        $backgroundStyle = "background: url('{$giftCard->backgroundImage->image_url}') center/cover no-repeat; position: relative;";
        $hasImage = true;
    } elseif ($giftCard->background_color) {
        $backgroundStyle = "background: {$giftCard->background_color}; position: relative;";
    } else {
        $backgroundStyle = 'background: #00ABB6; position: relative;';
    }
@endphp
<div class="flex justify-center items-center py-8">
    <div class="relative w-full max-w-xs">
        <div class="rounded-2xl shadow-xl" style="{{ $backgroundStyle }} min-height: 240px;">
            @if($hasImage)
                <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.18); border-radius: 1rem;"></div>
            @endif
            <div class="relative z-10 flex flex-col items-center justify-center text-center px-6 py-8" style="min-height: 240px;">
                <div class="bg-white bg-opacity-90 rounded-xl px-4 py-6 mb-2 w-full flex flex-col items-center">
                    <div class="text-3xl font-extrabold text-teal-600 mb-1" style="letter-spacing: 1px;">
                        {{ number_format($giftCard->amount, 2) }}
                        <span class="text-lg font-semibold text-gray-700">{{ $giftCard->currency }}</span>
                    </div>
                    @if($giftCard->message)
                        <div class="text-base text-gray-700 italic mb-2">"{{ $giftCard->message }}"</div>
                    @endif
                    @if($giftCard->recipient_name)
                        <div class="text-sm text-gray-600 mb-1">
                            <strong>@lang('For'):</strong> {{ $giftCard->recipient_name }}
                        </div>
                    @endif
                    @if($giftCard->expires_at)
                        <div class="text-xs text-gray-500">
                            <i class="fa fa-clock"></i> @lang('Expires on'): {{ $giftCard->expires_at->format('Y-m-d') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>