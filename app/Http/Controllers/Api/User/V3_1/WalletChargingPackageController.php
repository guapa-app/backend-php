<?php

namespace App\Http\Controllers\Api\User\V3_1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseApiController;
use App\Contracts\Repositories\WalletChargingPackageInterface;
use App\Http\Resources\User\V3_1\WalletChargingPackageCollection;

class WalletChargingPackageController extends BaseApiController
{
    private $walletChargingPackageRepository;

    public function __construct(WalletChargingPackageInterface $walletChargingPackageRepository)
    {
        parent::__construct();
        $this->walletChargingPackageRepository = $walletChargingPackageRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $walletChargingPackages = $this->walletChargingPackageRepository->all($request);

        return WalletChargingPackageCollection::make($walletChargingPackages)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
