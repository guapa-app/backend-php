<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use App\Services\LoyaltyPointsService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Services\AdminUserPointHistoryService;

class ManageUserPoints extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     *  Confirmation dialog for this action
     */
    public function withConfirmationDialog()
    {
        return $this->confirmButtonText('Confirm')
            ->cancelButtonText('Cancel')
            ->confirmText('Are you sure you want to update points for this user?');
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $adminId = auth()->user()->id; // Get the admin ID of the logged-in admin
        $loyaltyPointsService = resolve(LoyaltyPointsService::class);
        $adminUserPointHistoryService = resolve(AdminUserPointHistoryService::class);
        foreach ($models as $user) {
            if ($fields->action_type === 'addition') {
                $loyaltyPointsService->addAdminUserPoints($user, $fields->points);
            } elseif ($fields->action_type === 'deduction') {
                $loyaltyPointsService->deductAdminUserPoints($user, $fields->points);
            }
            $adminUserPointHistoryService->addHistory($user->id, $adminId, $fields->action_type, $fields->points, $fields->reason);
        }

        return Action::message('Points have been updated successfully.');
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Select::make('Action Type', 'action_type')
                ->options([
                    'addition' => 'Add Points',
                    'deduction' => 'Deduct Points',
                ])
                ->rules('required'),

            Number::make('Points', 'points')
                ->min(1)
                ->rules('required', 'integer', 'min:1'),

            Text::make('Reason', 'reason')
                ->nullable()
                ->help('Reason for addition or deduction (optional)'),
        ];
    }
}
