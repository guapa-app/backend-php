<?php

namespace App\Nova;

use App\Traits\NovaReadOnly;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Textarea;

class Notifications extends Resource
{
    use NovaReadOnly;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = DatabaseNotification::class;

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
        return [
            ID::make(__('ID'), 'id')->sortable(),

            MorphTo::make(__('notifiable'), 'notifiable')->types([
                User::class,
                Vendor::class,
            ]),

            Textarea::make(__('type'), 'type')->showOnIndex(true),

            Textarea::make(__('data'), 'data')->resolveUsing(function ($value) {
                return json_encode($value);
            }),

            DateTime::make(__('read at'), 'read_at'),

            DateTime::make(__('created at'), 'created_at')->sortable()->readonly(),
        ];
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
