<?php

namespace App\Nova\Resources;

use App\Enums\SupportMessageStatus;
use App\Nova\Actions\ReplyToTicket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
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
        'id', 'subject', 'body', 'phone',
    ];

    public static function authorizedToCreate(Request $request)
    {
        return false;
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

            BelongsTo::make(__('user'), 'user', User::class)
                ->nullable(),
            BelongsTo::make(__('type'), 'supportMessageType', SupportMessageType::class)
                ->showCreateRelationButton()
                ->sortable()
                ->nullable(),

            Select::make(__('status'), 'status')
                ->options(SupportMessageStatus::toSelect())
                ->default(SupportMessageStatus::Pending)
                ->displayUsingLabels()
                ->required(),

//            BelongsTo::make('Parent Message', 'parent', self::class)->nullable(),
            HasMany::make('Replies', 'replies', self::class),

            Text::make('subject')->required(),

            Text::make('body')->required()
                ->onlyOnDetail(),

            Text::make('phone')->required(),

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
        return [
            new ReplyToTicket(),
        ];
    }

    public static function indexQuery(NovaRequest $request, $query): Builder
    {
        if ($request->viaResource() === null) {
            return $query->parents();
        }

        return parent::indexQuery($request, $query);
    }
}
