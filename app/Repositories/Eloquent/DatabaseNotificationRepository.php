<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\DatabaseNotificationRepositoryInterface;
use App\Models\User;
use App\Models\Vendor;
use App\Notifications\PushNotification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

/**
 * Setting Repository.
 */
class DatabaseNotificationRepository extends EloquentRepository implements DatabaseNotificationRepositoryInterface
{
    /**
     * Items per page for pagination.
     * @var int
     */
    public $perPage = 10;

    /**
     * Construct an instance of the repo.
     * @param DatabaseNotification $model
     */
    public function __construct(DatabaseNotification $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all settings.
     * @param Request $request
     * @return Collection
     */
    public function all(Request $request): object
    {
        return $this->model->query()->where('type', 'App\Notifications\PushNotification')->paginate(perPage: $request->perPage, page: $request->page);
    }

    /**
     * Create new setting and persist in db.
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        $image = '';
        if (isset($data['image'])) {
            $image = config('filesystems.disks.s3.url') . '/' . Storage::disk('s3')->put('public/notifications', $data['image'], 'public');
        }

        $recipients = match ($data['type']) {
            'user' => User::query()->find($data['recipients']),
            'vendor' => Vendor::query()->find($data['recipients']),
            default => User::query()->whereHas('devices', function ($query) use ($data) {
                $query->where('type', $data['type']);
            })->get(),
        };

        Notification::send($recipients, new PushNotification($data['title'], $data['summary'], $image));

        return DatabaseNotification::query()->latest()->first();
    }

    public function getOne($id, $where = []): ?Model
    {
        return $this->model->query()->find($id);
    }
}
