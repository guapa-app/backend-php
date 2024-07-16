<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Repositories\DatabaseNotificationRepositoryInterface;
use App\Http\Requests\NotificationRequest;
use App\Models\Media;
use App\Models\User;
use App\Models\Vendor;
use App\Services\DatabaseNotificationService;
use Exception;
use Illuminate\Http\Request;

class DatabaseNotificationController extends BaseAdminController
{
    private $notificationRepository;
    private $notificationService;

    public function __construct(DatabaseNotificationRepositoryInterface $notificationRepository, DatabaseNotificationService $notificationService)
    {
        parent::__construct();

        $this->notificationRepository = $notificationRepository;
        $this->notificationService = $notificationService;
    }

    public function users(Request $request)
    {
        $records = User::all();

        return response()->json(["data" => $records]);
    }

    public function vendors(Request $request)
    {
        $records = Vendor::all();

        return response()->json(["data" => $records]);
    }

    public function index(Request $request)
    {
        $notifications = $this->notificationRepository->all($request);

        return response()->json($notifications);
    }

    public function single($id)
    {
        $notification = $this->notificationRepository->getOne($id);

        return response()->json($notification);
    }

    public function create(NotificationRequest $request)
    {
        try {
            // Create the notification
            $notification = $this->notificationService->create($request->validated());

            return response()->json($notification);
        } catch (Exception $exception) {
            logger($exception);
        }
    }
}
