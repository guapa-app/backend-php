<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\WheelSpin;

class WheelOfFortuneService
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Wheel Spin
     *
     * @param  mixed $userId
     * @param  mixed $wheelId
     * @param  mixed $points
     * @return void
     */
    public function wheelSpin(int $userId, int $wheelId, int $points)
    {
        WheelSpin::create([
            'user_id' => $userId,
            'wheel_id' => $wheelId,
            'points_awarded' => $points,
            'spin_date' => Carbon::now(),
        ]);
    }
}
