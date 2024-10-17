<?php

namespace App\Nova\Resources;

use App\Enums\AppointmentOfferEnum;
use App\Traits\NovaReadOnly;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphOne;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Fields\Textarea;
use Michielfb\Time\Time;

class AppointmentOffer extends Resource
{
    use NovaReadOnly;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\AppointmentOffer::class;
    public static $displayInNavigation = true;

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

            BelongsTo::make(__('User'), 'user', User::class),

            BelongsTo::make(__('Taxonomy'), 'taxonomy', Category::class),
            Status::make('Status')
                ->loadingWhen(['pending'])
                ->failedWhen(['refund']),
//                ->field(function () {
//                    return Select::make('Status')
//                        ->options(AppointmentOfferEnum::class)
//                        ->displayUsingLabels();
//                }),

            HasMany::make('Details', 'details', AppointmentOfferDetails::class),
            MorphOne::make('Invoice', 'invoice', Invoice::class),
            Textarea::make(__('notes'), 'notes')->nullable(),

        ];

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
