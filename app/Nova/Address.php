<?php

namespace App\Nova;

use Bissolli\NovaPhoneField\PhoneNumber;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Address extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Address::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

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

            Text::make(__('title'), 'title')
                ->sortable()
                ->rules('required', 'max:191'),

            Text::make(__('address 1'), 'address_1')
                ->sortable()
                ->rules('required', 'max:191'),

            Text::make(__('address 2'), 'address_2')
                ->sortable()
                ->rules('required', 'max:191'),

            BelongsTo::make(__('city'), 'city', City::class)->showCreateRelationButton(),

            MorphTo::make(__('addressable'), 'addressable')->types([
                User::class,
                Vendor::class,
            ]),

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

            Text::make(__('postal code'), 'postal_code')->required(),

            Select::make(__('type'), 'type')
                ->options(\App\Models\Address::TYPES)
                ->displayUsingLabels()
                ->required(),

            Number::make(__('lat'), 'lat')->step(0.00000001)->required(),
            Number::make(__('lng'), 'lng')->step(0.00000001)->required(),

            DateTime::make(__('created at'), 'created_at')->exceptOnForms()->readonly(),
            DateTime::make(__('updated at'), 'updated_at')->exceptOnForms()->readonly(),
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
