<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\AppointmentOfferRepositoryInterface;
use App\Enums\AppointmentOfferEnum;
use App\Http\Requests\V3_1\Common\AppointmentOfferRequest;
use App\Models\AppointmentOffer;
use App\Models\AppointmentOfferDetail;
use App\Models\Order;
use App\Services\V3_1\AppointmentOfferService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AppointmentRepository extends EloquentRepository implements AppointmentOfferRepositoryInterface
{
    public $perPage = 10;

    public function __construct(public AppointmentOfferService $appointmentOfferService, AppointmentOffer $model)
    {
        parent::__construct($model);
    }

    public function all(Request $request): object
    {
        $user = auth('api')->user();
        $vendor = $user->vendor ?? null;

        $query = AppointmentOffer::query()
            ->applyFilters($request)
            ->applyOrderBy($request->get('sort'), $request->get('order'))
            ->withListRelations($request)
            ->withListCounts($request)
            ->withSingleRelations($request)
            ->when($vendor, function ($query) use ($vendor) {
                $query->where('status', '!=' , AppointmentOfferEnum::Pending->value)->whereHas('details', function ($query) use ($vendor) {
                    $query->where('vendor_id', $vendor->id);
                })->with(['details' => function ($query) use ($vendor) {
                    $query->where('vendor_id', $vendor->id);
                }]);
            })
            ->when(!$vendor, function ($query) use ($user) {
                    $query->where('user_id', $user->id);
            });


        if ($request->has('perPage')) {
            $perPage = (int) ($request->has('perPage') ? $request->get('perPage') : $this->perPage);

            if ($perPage > 50) {
                $perPage = 50;
            }

            return $query->paginate($perPage);
        }
        return $query->get();

    }

    public function getOneWithRelations(int $id, $where = []): ?AppointmentOffer
    {
        if ($id > 0) {
            $where['id'] = $id;
        }
        $user = auth('api')->user();
        $vendor = $user->vendor ?? null;
        return $this->model->where($where)
            ->withSingleRelations()
            ->when($vendor, function ($query) use ($vendor) {
                $query->with(['details' => function ($query) use ($vendor) {
                    $query->where('vendor_id', $vendor->id);
                }]);
            })
            ->firstOrFail();
    }

}
