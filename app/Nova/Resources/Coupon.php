<?php

namespace App\Nova\Resources;

use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Http\Requests\NovaRequest;
use ZiffMedia\NovaSelectPlus\SelectPlus;

class Coupon extends Resource
{
    public static $model = \App\Models\Coupon::class;

    public static $title = 'code';

    public static $search = [
        'id', 'code',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Code')
                ->sortable()
                ->rules('required', 'max:255', 'unique:coupons,code,{{resourceId}}')
                ->readonly($request->isUpdateOrUpdateAttachedRequest()),

            Number::make('Discount Percentage')
                ->min(0)
                ->max(100)
                ->step(0.01)
                ->sortable()
                ->rules('required', 'numeric', 'min:0', 'max:100')
                ->readonly($request->isUpdateOrUpdateAttachedRequest()),

            Select::make('Discount Source')
                ->options([
                    'vendor' => 'Vendor',
                    'app' => 'Guapa',
                    'both' => 'Both',
                ])
                ->sortable()
                ->rules('required'),

            DateTime::make('Expires At')
                ->sortable()
                ->rules('required', 'after:today'),

            Number::make('Usage Count', function () {
                return $this->usages()->sum('usage_count');
            })->exceptOnForms(),


            Number::make('Max Uses')
                ->min(0)
                ->step(1)
                ->rules('required'),

            Number::make('Single User Usage')
                ->min(0)
                ->step(1)
                ->default(1)
                ->rules('required'),


            SelectPlus::make('Products', 'products', 'App\Nova\Resources\Product')
                ->label('title')
                ->readonly($request->isUpdateOrUpdateAttachedRequest()),

            // SelectPlus for vendors
            SelectPlus::make('Vendors', 'vendors', 'App\Nova\Resources\Vendor')
                ->label('name')->readonly($request->isUpdateOrUpdateAttachedRequest()),

            // SelectPlus for categories
            SelectPlus::make('Categories', 'categories', 'App\Nova\Resources\Category')
                ->label('title')
                ->optionsQuery(function ($query) {
                    $query->whereIn('type', ['specialty', 'category']);
                })->readonly($request->isUpdateOrUpdateAttachedRequest()),
        ];
    }


    public function cards(NovaRequest $request)
    {
        return [];
    }

    public function filters(NovaRequest $request)
    {
        return [];
    }

    public function lenses(NovaRequest $request)
    {
        return [];
    }

    public function actions(NovaRequest $request)
    {
        return [];
    }
    public static function fill(NovaRequest $request, $model)
    {
        if ($request->isCreateOrAttachRequest()) {
            $model->admin_id = Auth::id();
        }

        return parent::fill($request, $model);
    }
}
