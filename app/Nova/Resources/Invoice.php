<?php

namespace App\Nova\Resources;

use Illuminate\Http\Request;
use Laravel\Nova\Actions;
use Laravel\Nova\Actions\ExportAsCsv;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class Invoice extends Resource
{
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

            Badge::make('Status')->map([
                'paid'       => 'success',
                'pending'    => 'warning',
                'refunded'   => 'danger',
                'initiated'  => 'info',
                'created'    => 'warning',
                'attempted'  => 'warning',
                'authorized' => 'warning',
                'failed'     => 'warning',
                'canceled'   => 'warning',
                'expired'    => 'warning',
                'invalided'  => 'warning',
                'cod'        => 'warning',
            ])->withIcons(),

            DateTime::make(__('created at'), 'created_at')->onlyOnDetail()->readonly(),
            DateTime::make(__('updated at'), 'updated_at')->onlyOnDetail()->readonly(),

        ];

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
        return [
            ExportAsCsv::make()->nameable()
                ->withFormat(function ($model) {
                    return [
                        'ID'                    => $model->getKey(),
                        'Status'                => $model->status,
                        'Taxes'                 => $model->taxes,
                        'Amount Without taxes'  => $model->amount_without_taxes,
                        'Amount'                => $model->amount,
                        'Amount Formatted'      => $model->amount_format,
                        'Invoice ID'            => $model->invoice_id,
                    ];
                }),

            (new \App\Nova\Actions\DownloadInvoice)->showInline(),
        ];
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

    public function authorizedToReplicate(Request $request)
    {
        return false;
    }
}
