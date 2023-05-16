<?php

namespace App\Nova\Resources;

use Bissolli\NovaPhoneField\PhoneNumber;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class SupportMessage extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\SupportMessage::class;

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
        'subject', 'body', 'phone'
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

            BelongsTo::make(__('user'), 'user', User::class)->showCreateRelationButton(),

            Text::make('subject')->required(),

            Text::make('body')->required(),

            PhoneNumber::make(__('phone'), 'phone')
//                ->resolveUsing(function ($value) {
//                    return $value;
//                })
                ->fillUsing(function (NovaRequest $request, $model, $attribute, $requestAttribute) {
                    $value = $request[$requestAttribute];
                    $string = str_replace(' ', '-', $value); // Replaces all spaces with hyphens.
                    $string = preg_replace('/[^0-9]/', '', $string); // Removes special chars.
                    $model->{$attribute} = $string;
                })
                ->required()
                ->onlyCountries('SA', 'EG'),

            DateTime::make(__('read at'), 'read_at'),

            DateTime::make(__('created at'), 'created_at')->onlyOnDetail()->readonly(),
            DateTime::make(__('updated at'), 'updated_at')->onlyOnDetail()->readonly(),
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
