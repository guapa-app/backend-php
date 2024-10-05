<?php

namespace App\Nova\Resources;

use App\Nova\Actions\ClearSort;
use App\Nova\Actions\RandomizeMissingSortOrder;
use App\Nova\Actions\RandomizeSort;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use ZiffMedia\NovaSelectPlus\SelectPlus;

class Product extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Product::class;

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
        'title',
        'description',
        'terms',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param Request $request
     * @return array
     */
    public function fields(Request $request): array
    {
        $returned_arr = [
            Images::make(__('images'), 'products') // second parameter is the media collection name
                ->temporary(now()->addMinutes(5))
                ->rules('required'), // validation rules

            ID::make(__('ID'), 'id')->sortable(),

            Number::make('Sort Order')
                ->sortable()
                ->rules('nullable', 'integer'),

            Text::make(__('title'), 'title')->required()->rules('required'),

            Select::make(__('type'), 'type')
                ->options([
                    'product' => 'Products',
                    'service' => 'Procedures',
                ])
                ->filterable()
                ->displayUsingLabels()
                ->rules('required'),

            Text::make(__('url'), 'url')->nullable(),
            Textarea::make(__('description'), 'description')->required(),
            Textarea::make(__('terms'), 'terms')->required(),
            Number::make(__('price'), 'price')->step(0.01)->required()->rules('required'),
            Number::make(__('points'), 'earned_points')->rules('integer', 'min:0'),
            SelectPlus::make(__('categories'), 'taxonomies', Category::class)
                ->required()
                ->help('Only first category \'ll be stored')
                ->label(function ($state) {
                    return $state->title . ' - (' . $state->type . ')';
                })
                ->dependsOn(
                    ['type'],
                    function (SelectPlus $field, NovaRequest $request, FormData $formData) {
                        $type = $formData->type != null ? ($formData->type == 'service' ? 'specialty' : 'category') : '';

                        $field->optionsQuery(function ($query) use ($type) {
                            $query->where('type', '=', $type);
                        });
                    }
                )
                ->rules('required', function ($attribute, $value, $fail) use ($request) {
                    if (preg_match('/^\[({.*?})/', $request->taxonomies, $matches)) {
                        $firstObject = $matches[1];

                        $request->merge(['taxonomies' => '[' . $firstObject . ']']);
                    } else {
                        return $fail('The categories field is required.');
                    }
                }),

            Select::make(__('status'), 'status')
                ->options([
                    'Published' => 'Published',
                    'Draft' => 'Draft',
                ])
                ->default('Draft')
                ->displayUsingLabels()
                ->required()
                ->rules('required'),

            Select::make(__('review'), 'review')
                ->options([
                    'Approved' => 'Approved',
                    'Blocked' => 'Blocked',
                    'Pending' => 'Pending',
                ])
                ->default('Pending')
                ->displayUsingLabels()
                ->required()
                ->rules('required'),

            SelectPlus::make(__('addresses'), 'addresses', Address::class)
                ->label(function ($state) {
                    return $state->title ?? $state->address_1 ?? $state->address_2 ?? ' - (vId:' . $state->addressable_id . ')';
                })->optionsQuery(function ($query) {
                    $query->where('addressable_type', 'vendor');
                }),

            HasMany::make(__('reviews'), 'reviews', Review::class),

            DateTime::make(__('created at'), 'created_at')->onlyOnDetail()->readonly(),
            DateTime::make(__('updated at'), 'updated_at')->onlyOnDetail()->readonly(),

            BelongsTo::make(__('vendor'), 'vendor', Vendor::class)
                ->withoutTrashed()
                ->showCreateRelationButton(),
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
        return [
            ClearSort::make()->standalone(),
            RandomizeSort::make()->standalone(),
            RandomizeMissingSortOrder::make()->standalone(),
        ];
    }

    public static function softDeletes()
    {
        return false;
    }
}
