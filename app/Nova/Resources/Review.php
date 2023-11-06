<?php

namespace App\Nova\Resources;

use App\Traits\NovaVendorAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Textarea;

class Review extends Resource
{
    use NovaVendorAccess;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Review::class;

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
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param Request $request
     */
    public function fields(Request $request)
    {
        $returned_arr = [
            ID::make(__('ID'), 'id')->sortable(),

            BelongsTo::make(__('user'), 'user', User::class)->showCreateRelationButton(),

            MorphTo::make(__('review for'), 'reviewable')->types([
                Vendor::class,
                Product::class,
            ]),

            Number::make(__('stars'), 'stars')
                ->step(1)
                ->required()
                ->min(0)->max(5),

            Textarea::make(__('comment'), 'comment')->required(),

            DateTime::make(__('created at'), 'created_at')->exceptOnForms()->readonly(),
            DateTime::make(__('updated at'), 'updated_at')->exceptOnForms()->readonly(),
        ];

        if (Auth::user()?->isVendor()) {
            switch ($this->resource->reviewable_type) {
                case 'vendor':
                    $flag = Auth::user()->vendor_id != $this->resource->reviewable->id;
                    break;
                case 'product':
                    $flag = Auth::user()->vendor_id != $this->resource->reviewable->vendor_id;
                    break;
                default:
                    $flag = true;
            }

            if ($request->isUpdateOrUpdateAttachedRequest() && $flag) {
                throw new \Exception('You do not have permission to access this page!', 403);
            }

            return $returned_arr;
        }

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
        return [];
    }

    public function authorizedToUpdate(Request $request)
    {
        return !Auth::user()?->isVendor();
    }
}
