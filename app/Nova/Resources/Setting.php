<?php

namespace App\Nova\Resources;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class Setting extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Setting::class;

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
        's_key',
        'instructions',
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
            Text::make('key', 's_key')->required()->readonly(!is_null($request->resourceId)),
            Text::make('value', 's_value')->required(),
            Text::make('instructions', 'instructions')->required(),

            DateTime::make(__('created at'), 'created_at')->onlyOnDetail()->readonly(),
            DateTime::make(__('updated at'), 'updated_at')->onlyOnDetail()->readonly(),
        ];
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }
}
