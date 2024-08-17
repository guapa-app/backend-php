<?php

namespace App\Repositories\Eloquent;


use App\Contracts\Repositories\CouponRepositoryInterface;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponRepository extends EloquentRepository implements CouponRepositoryInterface
{
    /**
     * Construct an instance of the repo.
     *
     * @param \App\Models\Coupon $model
     */
    public function __construct(Coupon $model)
    {
        parent::__construct($model);
    }

    public function all(Request $request): object
    {
        $query = Coupon::query()->when($request->has('vendor_id'), function ($q) use ($request) {
            $q->whereHas('vendors', function ($q) use ($request) {
                $q->where('vendor_id', $request->vendor_id);
            });
        })->with(['products'])->withSum('usages as total_usage_count', 'usage_count');

        if ($request->has('perPage')) {
            return $query->paginate($request->perPage);
        } else {
            return $query->get();
        }
    }
    public function create(array $data) : Coupon
    {
        return DB::transaction(function () use ($data) {
            $coupon = parent::create($data);

            if (isset($data['products'])) {
                $coupon->products()->attach($data['products']);
            }

            if (isset($data['vendors'])) {
                $coupon->vendors()->attach($data['vendors']);
            }

            return $coupon->load(['products', 'vendors']);
        });
    }

    public function findByCode(string $code): ?Coupon
    {
        return Coupon::where('code', $code)->first();
    }

    //delete
    public function destroy(Coupon $coupon)
    {
        return $coupon->delete();
    }



}
