<?php

namespace App\Nova\Resources;

use App\Nova\Actions\ChangeOrderStatus;
use App\Traits\NovaVendorAccess;
use Bissolli\NovaPhoneField\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Order extends Resource
{
    use NovaVendorAccess;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order::class;

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
        'note',
        'name',
        'phone',
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

            BelongsTo::make(__('address'), 'address', Address::class)->showCreateRelationButton(),

            Number::make(__('total'), 'total')->step(0.001),

            Text::make(__('note'), 'note'),

            Text::make(__('name'), 'name'),

            PhoneNumber::make(__('phone'), 'phone')
//                ->resolveUsing(function ($value) {
//                    return $value;
//                })
                ->fillUsing(function (NovaRequest $request, $model, $attribute, $requestAttribute) {
                    $value = $request[$requestAttribute];
                    $string = str_replace(' ', '-', $value); // Replaces all spaces with hyphens.
                    $string = preg_replace('/[^0-9]/', '', $string); // Removes special chars.
                    $model->{$attribute} = $string;
                })
                ->required()
                ->onlyCountries('SA', 'EG'),

            Select::make(__('status'), 'status')
                ->default(2)
                ->options([
                    'Pending' => 'Pending',
                    'Accepted' => 'Accepted',
                    'Canceled' => 'Canceled',
                    'Rejected' => 'Rejected',
                ])
                ->displayUsingLabels()
                ->required(),

            HasMany::make(__('items'), 'items', OrderItem::class),

            DateTime::make(__('created at'), 'created_at')->exceptOnForms()->readonly(),
            DateTime::make(__('updated at'), 'updated_at')->onlyOnDetail()->readonly(),
        ];

        if (Auth::user()?->isVendor()) {
            if ($request->isUpdateOrUpdateAttachedRequest() && Auth::user()->vendor_id != $this->resource->vendor_id) {
                throw new \Exception('You do not have permission to access this page!', 403);
            }
            return $returned_arr;
        }

        return array_merge($returned_arr, [
            BelongsTo::make(__('user'), 'user', User::class)->showCreateRelationButton(),
            BelongsTo::make(__('vendor'), 'vendor', Vendor::class)->showCreateRelationButton(),
        ]);

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
    public function actions(Request $request): array
    {
        return [
            ChangeOrderStatus::make()
                ->canSee(function ($req) {
                    return true;
                })
                ->canRun(function ($req) {
                    return true;
                }),
        ];
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
