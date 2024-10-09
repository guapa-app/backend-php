<?php

namespace App\Http\Controllers\Api\User\V3_1;

use Carbon\Carbon;
use App\Models\WheelSpin;
use Illuminate\Http\Request;
use App\Models\WheelOfFortune;
use App\Services\LoyaltyPointsService;
use App\Services\WheelOfFortuneService;
use App\Http\Resources\WheelOfFortuneResource;
use App\Http\Controllers\Api\BaseApiController;
use App\Contracts\Repositories\WheelOfFortuneInterface;

class WheelOfFortuneController extends BaseApiController
{

    private $wheelOfFortuneRepository;
    protected $loyaltyPointsService;
    protected $wheelOfFortuneService;

    public function __construct(WheelOfFortuneInterface $wheelOfFortuneRepository, LoyaltyPointsService $loyaltyPointsService, WheelOfFortuneService $wheelOfFortuneService)
    {
        parent::__construct();
        $this->wheelOfFortuneRepository = $wheelOfFortuneRepository;
        $this->loyaltyPointsService = $loyaltyPointsService;
        $this->wheelOfFortuneService = $wheelOfFortuneService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $wheelOfFortunes = $this->wheelOfFortuneRepository->all($request);

        return WheelOfFortuneResource::collection($wheelOfFortunes)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function spinWheel(Request $request)
    {

        $request->validate([
            'wheel_id' => 'required|exists:wheel_of_fortunes,id',
        ]);

        $userId = auth()->id();
        $wheelId = $request->get('wheel_id');

        // Check if the customer has already spun today
        $lastSpin = WheelSpin::where('user_id', $userId)
            ->orderBy('spin_date', 'desc')
            ->first();

        if ($lastSpin) {
            $spinDate = Carbon::parse($lastSpin->spin_date);
            // Check if 24 hours have passed since the last spin
            if ($spinDate->diffInHours(Carbon::now()) < 24) {
                return $this->errorJsonRes(message: __('You can only spin the wheel once every 24 hours.'));
            }
        }

        $wheel = WheelOfFortune::findOrFail($wheelId);

        // Award the points to the customer
        $this->loyaltyPointsService->addSpinWheelPoints($userId, $wheel);

        // Log user wheel spin
        $this->wheelOfFortuneService->wheelSpin($userId, $wheelId, $wheel->points);

        return response()->json([
            'data' => [
                'message' => __('You have been awarded :points points!', [
                    'points' => $wheel->points
                ]),
            ],
            'success' => true,
            'message' => __('api.success'),
        ]);
    }

    public function lastSpinWheelDate(Request $request)
    {
        $userId = $request->user()->id;

        // Check if the customer has already spun today
        $lastSpin = WheelSpin::where('user_id', $userId)
            ->orderBy('spin_date', 'desc')
            ->first();

        if ($lastSpin) {
            $spinDate = Carbon::parse($lastSpin->spin_date);
            if ($spinDate->diffInHours(Carbon::now()) < 24) {
                return response()->json([
                    'data' => [
                        'date' => $lastSpin->spin_date->format('Y-m-d H:i:s'),
                    ],
                    'success' => true,
                    'message' => __('api.success'),
                ]);
            }
        }

        return response()->json([
            'data' => [
                'date' => "",
            ],
            'success' => true,
            'message' => __('api.success'),
        ]);
    }
}
