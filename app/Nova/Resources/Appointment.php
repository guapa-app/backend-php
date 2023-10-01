<?php

namespace App\Nova\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Michielfb\Time\Time;

class Appointment extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Appointment::class;
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
     * @param Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        $returned_arr = [
            ID::make(__('ID'), 'id')->sortable(),

            Time::make(__('from time'), 'from_time')
                ->format('hh:mm A'),

            Time::make(__('to time'), 'to_time')
                ->format('hh:mm A'),

            BelongsTo::make(__('vendor'), 'vendor', Vendor::class)->showCreateRelationButton(),
        ];

        if (Auth::user()?->isVendor()) {
            if ($request->isUpdateOrUpdateAttachedRequest() && Auth::user()->vendor_id != $this->resource->vendor_id) {
                throw new \Exception('You do not have permission to access this page!', 403);
            }
            return $returned_arr;
        }

        return $returned_arr;
    }

    /**
     * Get the cards available for the request.
     *
     * @param Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
