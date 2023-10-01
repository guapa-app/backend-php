<?php

namespace App\Nova\Resources;

use App\Traits\NovaVendorAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class Invoice extends Resource
{
    use NovaVendorAccess;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Invoice::class;

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
        'invoice_id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $returned_arr = [
            ID::make(__('ID'), 'id')->sortable(),

            BelongsTo::make(__('order'), 'order', Order::class),

            Text::make(__('invoice id'), 'invoice_id')->required(),
            Text::make(__('url'), 'url')->nullable(),
            Textarea::make(__('description'), 'description')->required(),
            Number::make(__('amount'), 'amount')->step(0.01)->required(),
            Text::make(__('currency'), 'currency')->required(),

            Select::make(__('status'), 'status')
                ->options([
                    'Paid' => 'Paid',
                    'Pending' => 'Pending',
                    'Refunded' => 'Refunded',
                    'Initial' => 'Initial',
                ])
                ->default('Initial')
                ->displayUsingLabels()
                ->required(),

            DateTime::make(__('created at'), 'created_at')->onlyOnDetail()->readonly(),
            DateTime::make(__('updated at'), 'updated_at')->onlyOnDetail()->readonly(),

        ];

        if (Auth::user()?->isVendor()) {
            if ($request->isUpdateOrUpdateAttachedRequest() && Auth::user()->vendor_id != $this->resource->order->vendor_id) {
                throw new \Exception('You do not have permission to access this page!', 403);
            }
            return $returned_arr;
        }

        return $returned_arr;
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    public static function authorizedToCreate(Request $request): bool
    {
        return false;
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
