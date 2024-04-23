<?php

namespace App\Nova\Resources;

use Bissolli\NovaPhoneField\PhoneNumber;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

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
        'id', 'name', 'email',
    ];

    /**
     * The relationships that should be eager loaded when performing an index query.
     *
     * @var array
     */
    public static $with = ['profile'];

    public static function indexQuery(NovaRequest $request, $query): Builder
    {
        $query->with(['profile']);

        return parent::indexQuery($request, $query);
    }

    protected static function fillFields(NovaRequest $request, $model, $fields): array
    {
        $profileFields = [
            'firstname' => $request->input('profile_first_name'),
            'lastname' => $request->input('profile_last_name'),
            'gender' => $request->input('profile_gender'),
        ];

        $result = parent::fillFields($request, $model, $fields);

        if ($model instanceof \App\Models\User) {
            // Insert them in the details object after model has been saved.
            $result[1][] = function () use ($profileFields, $model) {
                $model->profile()->updateOrCreate([
                    'user_id' => $model->id,
                ], $profileFields);
            };
        }

        return $result;
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
            ID::make()->sortable(),

//            Images::make(__('photo'), 'photo') // second parameter is the media collection name
//    ->temporary(now()->addMinutes(5))
//            ->conversionOnIndexView('small') // conversion used to display the image
//            ->rules('required'), // validation rules

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

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
                ->updateRules('unique:users,phone,{{resourceId}}')
                ->onlyCountries('SA', 'EG'),

            Select::make(__('status'), 'status')
                ->options([
                    \App\Models\User::STATUS_ACTIVE => '✔️active',
                    \App\Models\User::STATUS_CLOSED => '❌ disabled',
                    \App\Models\User::STATUS_DELETED => '🗑️ deleted',
                ])
                ->displayUsingLabels()
                ->required(),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),

            DateTime::make(__('phone verified at'), 'phone_verified_at'),
            DateTime::make(__('created at'), 'created_at')->onlyOnDetail()->readonly(),
            DateTime::make(__('updated at'), 'updated_at')->onlyOnDetail()->readonly(),

            Panel::make(__('Profile'), $this->profileFields()),
            MorphMany::make(__('Devices'), 'devices', Device::class),
            HasMany::make(__('histories'), 'histories'),
            HasMany::make(__('support messages'), 'support_messages'),
        ];

        return $returned_arr;
    }

    public function profileFields(): array
    {
        return [
            Text::make(__('First Name'), 'profile.first_name')
                ->sortable()
                ->rules('string', 'max:191'),

            Text::make(__('Last Name'), 'profile.last_name')
                ->sortable()
                ->rules('string', 'max:191'),

            Select::make(__('Gender'), 'profile.gender')
                ->sortable()
                ->options([
                    'Male' => '👨 Male',
                    'Female' => '👩 Female',
                    'Other' => '- Other',
                ])->displayUsingLabels(),
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
