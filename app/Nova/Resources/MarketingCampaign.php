<?php

namespace App\Nova\Resources;

use App\Models\MarketingCampaign as MarketingCampaignModel;
use App\Traits\NovaReadOnly;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class MarketingCampaign extends Resource
{
    use NovaReadOnly;

    public static $model = MarketingCampaignModel::class;

    public static $title = 'id';

    public static $search = [
        'id', 'channel', 'audience_type', 'status',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Vendor'),

            Text::make('Type', function () {
                if ($this->campaignable instanceof \App\Models\Product) {
                    return $this->campaignable->type ?? 'N/A';
                } else {
                    return class_basename($this->campaignable_type) ?? 'N/A';
                }
            })->readonly(),

            Select::make('Channel')
                ->options(MarketingCampaignModel::CHANNEL)
                ->displayUsingLabels(),

            Select::make('Audience Type', 'audience_type')
                ->options([
                    'vendor_customers' => 'Vendor Customers',
                    'guapa_customers' => 'Guapa Customers',
                ])
                ->displayUsingLabels(),

            Number::make('Audience Count'),

            Currency::make('Message Cost')
                ->currency('SAR'),

            Currency::make('Taxes')
                ->currency('SAR'),

            Currency::make('Total Cost')
                ->currency('SAR'),

            Select::make('Status')
                ->options([
                    'pending' => 'Pending',
                    'completed' => 'Completed',
                    'expired' => 'Expired',
                    'failed' => 'Failed',
                ])
                ->displayUsingLabels(),

            Text::make('Invoice URL')
                ->asHtml()
                ->displayUsing(function ($url) {
                    return "<a href='{$url}' target='_blank' style='color: blue;'>View</a>";
                }),

            BelongsToMany::make('Users'),

            DateTime::make('Created At')
                ->sortable()
                ->hideFromIndex(),

            DateTime::make('Updated At')
                ->sortable()
                ->hideFromIndex(),
            new Panel('Item Details', $this->productOfferFields()),
        ];
    }

    protected function productOfferFields()
    {
        return [
            MorphTo::make('Item', 'campaignable')
                ->types([
                    Product::class,
                    Offer::class,
                ])
                ->readonly(),
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->with(['campaignable', 'vendor', 'users']);
    }

    public function cards(NovaRequest $request)
    {
        return [];
    }

    public function filters(NovaRequest $request)
    {
        return [];
    }

    public function lenses(NovaRequest $request)
    {
        return [];
    }

    public function actions(NovaRequest $request)
    {
        return [];
    }
}
