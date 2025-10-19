<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\CityTrend;
use App\Nova\Metrics\CommentTrend;
use App\Nova\Metrics\OfferTrend;
use App\Nova\Metrics\OrderTrend;
use App\Nova\Metrics\PostTrend;
use App\Nova\Metrics\ProductTrend;
use App\Nova\Metrics\ReviewTrend;
use App\Nova\Metrics\SupportMessageTrend;
use App\Nova\Metrics\TaxonomyTrend;
use App\Nova\Metrics\UserTrend;
use App\Nova\Metrics\VendorTrend;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            CityTrend::make()->width('1/2'),
            CommentTrend::make()->width('1/2'),
            OfferTrend::make()->width('1/2'),
            OrderTrend::make()->width('1/2'),
            PostTrend::make()->width('1/2'),
            ProductTrend::make()->width('1/2'),
            ReviewTrend::make()->width('1/2'),
            SupportMessageTrend::make()->width('1/2'),
            TaxonomyTrend::make()->width('1/2'),
            UserTrend::make()->width('1/2'),
            VendorTrend::make()->width('1/2'),
        ];
    }
}
