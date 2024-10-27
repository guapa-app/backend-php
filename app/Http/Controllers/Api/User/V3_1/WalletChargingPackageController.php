<?php

namespace App\Http\Controllers\Api\User\V3_1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseApiController;
use App\Contracts\Repositories\WalletChargingPackageInterface;
use App\Http\Resources\WalletChargingPackageResource;

class WalletChargingPackageController extends BaseApiController
{
    private $walletChargingPackageRepository;

    public function __construct(WalletChargingPackageInterface $walletChargingPackageRepository)
    {
        $this->walletChargingPackageRepository = $walletChargingPackageRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $walletChargingPackages = $this->walletChargingPackageRepository->all($request);

        return WalletChargingPackageResource::collection($walletChargingPackages)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
