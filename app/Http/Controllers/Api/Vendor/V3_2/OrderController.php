<?php

namespace App\Http\Controllers\Api\Vendor\V3_2;

use App\Enums\OrderStatus;
use App\Enums\ProductType;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Vendor\V3_2\OrderResource;
use App\Http\Resources\Vendor\V3_2\OrderCollection;

class OrderController extends BaseApiController
{
    public function index(Request $request)
    {
        $statusId = $request->query('status_id');
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 15);
        $typeId = $request->query('type_id');

        $query = $this->user->orders()->withApiListRelations($request);

        // Filter by status group (1=Active, 2=Completed, 3=Inactive)
        if ($statusId && in_array($statusId, [1, 2, 3])) {
            $statuses = OrderStatus::getStatusGroup($statusId);
            $query->whereIn('status', $statuses);
        }

        // Filter by product type (1=Product, 2=Service)
        if ($typeId && in_array($typeId, [1, 2])) {
            $query->hasProductTypeInt($typeId);
        }

        $orders = $query->paginate($perPage, ['*'], 'page', $page);

        return OrderCollection::make($orders)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function single($id)
    {
        $order = $this->user->orders()
            ->withSingleRelations()
            ->findOrFail($id);

        return OrderResource::make($order)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
