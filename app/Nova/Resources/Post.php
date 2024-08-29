<?php

namespace App\Nova\Resources;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Inspheric\Fields\Url;
use Laravel\Nova\Fields;
use Laravel\Nova\Http\Requests\NovaRequest;

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
            Images::make(__('images'), 'posts') // second parameter is the media collection name
                ->temporary(now()->addMinutes(5))
                ->rules('required'), // validation rules

            Fields\ID::make(__('ID'), 'id')->sortable(),

            // TODO: Should this be hidden?
            Fields\BelongsTo::make(__('admin'), 'admin', Admin::class)->showCreateRelationButton(),

            Fields\BelongsTo::make(__('category'), 'category', Category::class)->showCreateRelationButton(),

            Fields\Text::make(__('title'), 'title')
                ->required()
                ->sortable()
                ->rules('required', 'max:191'),

            Fields\Trix::make(__('content'), 'content')
                ->required()
                ->sortable()
                ->rules('required'),

            Fields\Select::make(__('status'), 'status')
                ->default(2)
                ->options(\App\Models\Post::STATUSES)
                ->displayUsingLabels()
                ->required(),

            Url::make(__('youtube url'), 'youtube_url')->showOnIndex(false),

            Fields\HasMany::make(__('comments'), 'comments'),

            Fields\BelongsToMany::make(__('social media'))
                ->fields(function () {
                    return [
                        Url::make(__('link'), 'link'),
                    ];
                }),

            Fields\DateTime::make(__('created at'), 'created_at')->exceptOnForms()->readonly(),
            Fields\DateTime::make(__('updated at'), 'updated_at')->exceptOnForms()->readonly(),
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
