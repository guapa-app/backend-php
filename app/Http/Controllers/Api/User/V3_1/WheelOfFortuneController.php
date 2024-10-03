<?php

namespace App\Http\Controllers\Api\User\V3_1;

use Carbon\Carbon;
use App\Models\WheelSpin;
use Illuminate\Http\Request;
use App\Models\WheelOfFortune;
use App\Enums\LoyaltyPointAction;
use App\Services\LoyaltyPointsService;
use App\Http\Controllers\Api\BaseApiController;
use App\Contracts\Repositories\WheelOfFortuneInterface;
use App\Http\Resources\User\V3_1\WheelOfFortuneCollection;

class WheelOfFortuneController extends BaseApiController
{

    private $wheelOfFortuneRepository;
    protected $loyaltyPointsService;

    public function __construct(WheelOfFortuneInterface $wheelOfFortuneRepository, LoyaltyPointsService $loyaltyPointsService)
    {
        parent::__construct();
        $this->wheelOfFortuneRepository = $wheelOfFortuneRepository;
        $this->loyaltyPointsService = $loyaltyPointsService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $wheelOfFortunes = $this->wheelOfFortuneRepository->all($request);

        return WheelOfFortuneCollection::make($wheelOfFortunes)
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
                return response()->json(['message' => 'You can only spin the wheel once every 24 hours.'], 403);
            }
        }

        // Get the wheel settings by ID
        $wheelItems = WheelOfFortune::findOrFail($wheelId);

        // Award the points to the customer
        $this->loyaltyPointsService->addPoints($userId, $wheelItems->points, LoyaltyPointAction::SPIN_WHEEL->value);

        WheelSpin::create([
            'user_id' => $userId,
            'wheel_id' => $wheelId,
            'spin_date' => Carbon::now(),
        ]);

        return response()->json([
            'message' => __('You have been awarded ' . $wheelItems->points . ' points!'),
            'points' => $wheelItems->points
        ]);
    }
}
