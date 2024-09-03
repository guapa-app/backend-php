<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
enum ListTypeEnum: string
{
    case Default = 'default';
    case MostViewed = 'most_viewed';
    case MostOrdered = 'most_ordered';
    case Offers = 'offers';
}
