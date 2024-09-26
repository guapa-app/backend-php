<?php

namespace App\Nova\Resources;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class AppointmentOfferDetails extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\AppointmentOfferDetail::class;
    public static $displayInNavigation = false;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $returned_arr = [
            ID::make(__('ID'), 'id')->sortable(),

            Text::make('status'),

            Number::make('Offer price', 'offer_price'),

            Textarea::make(__('Reject reason'), 'reject_reason')->nullable(),

            Textarea::make(__('Staff reason'), 'staff_notes')->nullable(),

            Textarea::make(__('Offer reason'), 'offer_notes')->nullable(),

            Textarea::make(__('Terms'), 'terms')->nullable(),

            DateTime::make(__('Starts at'), 'starts_at')->nullable(),

            DateTime::make(__('Expires at'), 'expires_at')->nullable(),
        ];

        return $returned_arr;
    }

    /**
     * Get the cards available for the request.
     *
     * @param  Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
