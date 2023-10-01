<?php

namespace App\Nova\Resources;

use App\Traits\NovaVendorAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;

class OrderItem extends Resource
{
    use NovaVendorAccess;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\OrderItem::class;
    public static $displayInNavigation = false;

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

    public static function authorizedToCreate(Request $request): bool
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
        $returned_arr = [
            ID::make(__('ID'), 'id')->sortable(),

            BelongsTo::make(__('order'), 'order', Order::class)->showCreateRelationButton(),

            BelongsTo::make(__('product'), 'product', Product::class)->showCreateRelationButton(),

            BelongsTo::make(__('offer'), 'offer', Offer::class)->showCreateRelationButton(),

            Number::make(__('amount'), 'amount')->step(0.001),

            Number::make(__('quantity'), 'quantity')->step(1),

            Text::make(__('appointment'), 'appointment'),

            DateTime::make(__('created at'), 'created_at')->onlyOnDetail()->readonly(),
            DateTime::make(__('updated at'), 'updated_at')->onlyOnDetail()->readonly(),
        ];

        if (Auth::user()?->isVendor()) {
            if ($request->isUpdateOrUpdateAttachedRequest() && Auth::user()->vendor_id != $this->resource->product->vendor_id) {
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

    public function authorizedToUpdate(Request $request): bool
    {
        return false;
    }

    public function authorizedToDelete(Request $request): bool
    {
        return false;
    }
}
