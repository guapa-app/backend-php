<?php

namespace App\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\WalletChargingPackage;
use App\Contracts\Repositories\WalletChargingPackageInterface;

class WalletChargingPackageRepository extends EloquentRepository implements WalletChargingPackageInterface
{
    /**
     * Construct an instance of the repo.
     *
     * @param WalletChargingPackage $model
     */
    public function __construct(WalletChargingPackage $model)
    {
        parent::__construct($model);
    }

    public function all(Request $request): object
    {
        $query = WalletChargingPackage::query();

        if ($request->has('perPage')) {
            return $query->paginate($request->perPage);
        } else {
            return $query->get();
        }
    }

    public function findByCode(string $code): ?WalletChargingPackage
    {
        return WalletChargingPackage::where('code', $code)->first();
    }
}
