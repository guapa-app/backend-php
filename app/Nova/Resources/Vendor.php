<?php

namespace App\Nova\Resources;

use Bissolli\NovaPhoneField\PhoneNumber;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Inspheric\Fields\Email;
use Inspheric\Fields\Url;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasManyThrough;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Vendor extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Vendor::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
        'email',
        'phone',
        'about',
        'whatsapp',
        'twitter',
        'instagram',
        'snapchat',
        'working_hours',
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
            ID::make(__('ID'), 'id')->sortable(),

            BelongsTo::make(__('provider'), 'parent', self::class)->withoutTrashed(),

            Images::make(__('logo'), 'logos') // second parameter is the media collection name
                ->temporary(now()->addMinutes(5))
                    ->conversionOnIndexView('small') // conversion used to display the image
                    ->rules('required'), // validation rules

            Text::make(__('name'), 'name')
                ->sortable()
                ->required(),

            Text::make(__('email'), 'email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:vendors,email')
                ->updateRules('unique:vendors,email,{{resourceId}}'),

            Select::make(__('type'), 'type')
                ->options(\App\Models\Vendor::TYPES)
                ->displayUsingLabels()
                ->required(),

            Select::make(__('status'), 'status')
                ->options([
                    0 => 'disabled',
                    1 => 'active',
                ])
                ->displayUsingLabels()
                ->required(),

            Text::make(__('tax_number'), 'tax_number')
                ->nullable()
                ->showOnIndex(false),

            Text::make(__('cat_number'), 'cat_number')
                ->nullable()
                ->showOnIndex(false),

            Text::make(__('reg_number'), 'reg_number')
                ->nullable()
                ->showOnIndex(false),

            PhoneNumber::make('phone', 'phone')
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

            Textarea::make(__('about'), 'about')
                ->nullable(),

            Files::make(__('Contract'), 'contract')
                ->temporary(now()->addMinutes(5))
                ->rules('nullable') // Add appropriate validation rules
                ->help('Upload the vendor contract (PDF, DOC, or DOCX file)'),

            Panel::make(__('social media'), $this->socialMediaFields()),

            Text::make(__('working hours'), 'working_hours')
                ->required(),

            HasMany::make(__('appointments'), 'appointments', Appointment::class),

            HasMany::make(__('products'), 'products', Product::class),

            HasMany::make(__('orders'), 'orders', Order::class),

            HasManyThrough::make(__('invoices'), 'invoices', Invoice::class),

            BelongsToMany::make(__('staff'), 'staff', User::class)->fields(function () {
                return [
                    Email::make(__('email (staff)'), 'email')
                        ->sortable()
                        ->default('vendor-' . time() . '@cosmo.com')
                        ->alwaysClickable()
                        ->creationRules('unique:user_vendor,email')
                        ->updateRules('unique:user_vendor,email,{{resourceId}}'),

                    Select::make(__('role'), 'role')
                        ->required()
                        ->options([
                            'manager' => 'manager',
                            'doctor' => 'doctor',
                            'patient' => 'patient',
                        ]),
                ];
            }),

            HasMany::make(__('doctors'), 'children', self::class)->canSee(function () use ($request) {
                return $request->isResourceDetailRequest() && $this->resource?->isParent();
            }),

            Boolean::make(__('verified'), 'verified')->default(false),

            DateTime::make(__('created at'), 'created_at')->onlyOnDetail()->readonly(),
            DateTime::make(__('updated at'), 'updated_at')->onlyOnDetail()->readonly(),
        ];

        return $returned_arr;
    }

    public function socialMediaFields(): array
    {
        return [
            Url::make(__('twitter'), 'twitter')->showOnIndex(false),

            Url::make(__('instagram'), 'instagram')->showOnIndex(false),

            Url::make(__('snapchat'), 'snapchat')->showOnIndex(false),

            Url::make(__('snapchat'), 'snapchat')->showOnIndex(false),

            Url::make(__('website_url'), 'website_url')
                ->nullable()
                ->showOnIndex(false),

            Url::make(__('known_url'), 'known_url')
                ->nullable()
                ->showOnIndex(false),
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
