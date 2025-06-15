<?php

namespace App\Http\Controllers\Api\Vendor\V3_2;

use App\Enums\OrderStatus;
use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;

class OrderController extends BaseApiController
{
    public function index(Request $request)
    {
        $statusId = $request->query('status_id');
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 15);
        $typeId = $request->query('type_id');

        $query = auth()->user()->orders();

        if ($statusId) {
            $statuses = OrderStatus::getStatusGroup($statusId);
            $query->whereIn('status', $statuses);
        }

        if ($typeId) {
            $query->where('type_id', $typeId);
        }

        $orders = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'status' => true,
            'data' => $orders
        ]);
    }

    public function single($id)
    {
        $order = auth()->user()->orders()->findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => $order
        ]);
    }
}
