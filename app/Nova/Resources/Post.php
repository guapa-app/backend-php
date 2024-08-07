<?php

namespace App\Nova\Resources;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Inspheric\Fields\Url;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Trix;

class Post extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Post::class;

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
        'content',
    ];

    public static function relatableTaxonomies(NovaRequest $request, $query)
    {
        return $query->where('type', 'blog_category');
    }

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

            // TODO: Should this be hidden?
            BelongsTo::make(__('admin'), 'admin', Admin::class)->showCreateRelationButton(),

            BelongsTo::make(__('category'), 'category', Category::class)->showCreateRelationButton(),

            Text::make(__('title'), 'title')
                ->required()
                ->sortable()
                ->rules('required', 'max:191'),

                Trix::make(__('content'), 'content')
                ->required()
                ->sortable()
                ->rules('required'),

            Select::make(__('status'), 'status')
                ->default(2)
                ->options(\App\Models\Post::STATUSES)
                ->displayUsingLabels()
                ->required(),

            Url::make(__('youtube url'), 'youtube_url')->showOnIndex(false),

            Images::make(__('images'), 'posts') // second parameter is the media collection name
            ->temporary(now()->addMinutes(5))
                ->rules('required'), // validation rules

            HasMany::make(__('comments'), 'comments'),

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
