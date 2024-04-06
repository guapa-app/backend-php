<?php

namespace App\Nova\Resources;

use App\Models\Taxonomy;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Spatie\NovaTranslatable\Translatable;

class Category extends Resource
{
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

            Number::make(__('fees'), 'fees')
                ->help('Fees are the <strong>percentage</strong> value applied to the product. <strong>(example: a 10% fee on a 100 riyal product would result in a 10 riyal fee.)</strong>')
                ->placeholder('10 %')
                ->step(0.5)
                ->min(0)
                ->max(100)
                ->required()
                ->rules('required'),

            Select::make(__('type'), 'type')
                ->options([
                    'category'      => 'Products',
                    'specialty'     => 'Procedures',
                    'blog_category' => 'Blog',
                ])
                ->displayUsingLabels()
                ->required()
                ->rules('required'),

            BelongsTo::make('parent', 'parent', self::class)->showCreateRelationButton()->nullable(),

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
