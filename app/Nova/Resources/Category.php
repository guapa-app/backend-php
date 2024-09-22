<?php

namespace App\Nova\Resources;

use Alexwenzel\DependencyContainer\DependencyContainer;
use Alexwenzel\DependencyContainer\HasDependencies;
use App\Models\Taxonomy;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\NovaTranslatable\Translatable;

class Category extends Resource
{
    use HasDependencies;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Taxonomy::class;

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
        'title',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param Request $request
     * @return array
     */
    public function fields(Request $request): array
    {
        return [
            Images::make(__('icon'), config('taxonomies.icon_collection_name', 'taxonomy_icons'))
                ->temporary(now()->addMinutes(5))
                ->conversionOnIndexView('small') // conversion used to display the image
                ->rules('required'),

            ID::make(__('ID'), 'id')->sortable(),

            Translatable::make([
                Text::make(__('title'), 'title')
                    ->sortable()
                    ->rules('required', 'max:191'),

                Textarea::make(__('description'), 'description')
                    ->sortable()
                    ->rules('required'),
            ]),

            Select::make('Guapa Fees Options', 'guapa_fees')->options([
                0 => __('Fees'),
                1 => __('Fixed Price'),
            ])
                ->required()
                ->default(0)
                ->displayUsingLabels()
                ->showOnIndex(false),

            DependencyContainer::make([
                Number::make(__('Fees'), 'fees')
                    ->help('Fees are the <strong>percentage</strong> value applied to the product. <strong>(example: a 10% fee on a 100 riyal product would result in a 10 riyal fee.)</strong>')
                    ->placeholder('10 %')
                    ->step(0.5)
                    ->min(0)
                    ->max(100)
                    ->required()
                    ->rules('required_without:fixed_price'),
            ])->dependsOn('guapa_fees', 0),

            DependencyContainer::make([
                Number::make(__('Fixed Price'), 'fixed_price')
                    ->help('Fixed price is the amount that will deduct from user when he made an order<strong>(example: a 300 riyal will be deducted from a 1000 riyal product price)</strong>')
                    ->placeholder('300')
                    ->step(1)
                    ->min(0)
                    ->rules('required_without:fees'),
            ])->dependsOn('guapa_fees', 1),

            Number::make(__('Fees'), 'fees')->onlyOnIndex(),
            Number::make(__('Fixed Price'), 'fixed_price')->onlyOnIndex(),

            Select::make(__('type'), 'type')
                ->options([
                    'category' => 'Products',
                    'specialty' => 'Procedures',
                    'blog_category' => 'Blog',
                ])
                ->displayUsingLabels()
                ->required()
                ->rules('required'),

            BelongsTo::make('parent', 'parent', self::class)->showCreateRelationButton()->nullable(),

            Boolean::make(__('Is Appointment'), 'is_appointment'),
            DependencyContainer::make([
                Number::make(__('Appointment Price'), 'appointment_price')
                    ->step(1)
                    ->min(0),
            ])->dependsOn('is_appointment', true),

            DateTime::make(__('created at'), 'created_at')->exceptOnForms()->readonly(),
            DateTime::make(__('updated at'), 'updated_at')->exceptOnForms()->readonly(),
        ];
    }

    protected static function fillFields(NovaRequest $request, $model, $fields): array
    {
        if ($request->editMode == 'update') {
            if (isset($request->fixed_price)) {
                $model->forceFill([
                    'fees' => null,
                ])->save();
            } else {
                $model->forceFill([
                    'fixed_price' => null,
                ])->save();
            }
        }
        unset($fields[6]);
        $request->request->remove('guapa_fees');

        return parent::fillFields($request, $model, $fields);
    }

    /**
     * Get the cards available for the request.
     *
     * @param Request $request
     * @return array
     */
    public function cards(Request $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function filters(Request $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function lenses(Request $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function actions(Request $request): array
    {
        return [];
    }
}
