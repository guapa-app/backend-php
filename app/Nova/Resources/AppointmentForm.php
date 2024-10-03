<?php

namespace App\Nova\Resources;

use App\Enums\AppointmentTypeEnum;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class AppointmentForm extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\AppointmentForm::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return $this->id.' - '.$this->type->value.' - '.$this->key;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'key',
        'type'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [

            ID::make()->sortable(),
            Text::make('Key')->rules('required', 'max:255'),

            Select::make('Type')
                ->options(array_combine(AppointmentTypeEnum::getValues(), AppointmentTypeEnum::options()))
                ->displayUsingLabels()
                ->rules('required'),

            Textarea::make('Options')
                ->help('Options template based on the selected Type. You can modify this as needed.')
                ->dependsOn('type', function (Textarea $field, NovaRequest $request, FormData $formData) {
                    $field->default(function ($request) use ($formData) {
                        $selectedType = $formData->get('type');
                        $template = AppointmentTypeEnum::templates()[$selectedType] ?? '';
                        return $template;
                    });
                })
                ->rules(['nullable','json']),

            DateTime::make(__('created at'), 'created_at')
                ->onlyOnDetail()
                ->readonly(),

            DateTime::make(__('updated at'), 'updated_at')
                ->onlyOnDetail()
                ->readonly(),
        ];
    }    /**
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
