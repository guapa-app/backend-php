<?php

namespace App\Nova\Resources;

use Laravel\Nova\Resource;
use Laravel\Nova\Fields\ID;
use App\Nova\Resources\User;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Resources\WheelOfFortune;
use Laravel\Nova\Http\Requests\NovaRequest;

class WheelSpin extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\WheelSpin>
     */
    public static $model = \App\Models\WheelSpin::class;

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
        'user_id',
        'wheel_id',
        'points_awarded',
    ];

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToUpdate(Request $request): bool
    {
        return false;
    }

    public function authorizedToDelete(Request $request): bool
    {
        return false;
    }

    public function authorizedToReplicate(Request $request): bool
    {
        return false;
    }
    
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            // BelongsTo::make(__('user'), 'user', User::class),

            BelongsTo::make('User', 'user', User::class)
                ->sortable()
                ->searchable(),

            BelongsTo::make('Wheel', 'wheel', WheelOfFortune::class)
                ->sortable()
                ->searchable(),

            Number::make('Points Awarded', 'points_awarded')
                ->sortable(),

            DateTime::make('Spin Date', 'spin_date')
                ->sortable(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
