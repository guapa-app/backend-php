<?php

namespace App\Http\Controllers;

use App\Models\GiftCard;
use Illuminate\Http\Request;

class GiftCardPreviewController extends Controller
{
    public function userPreview($id)
    {
        $giftCard = GiftCard::findOrFail($id);
        return view('frontend.gift-cards.user-preview', compact('giftCard'));
    }
}