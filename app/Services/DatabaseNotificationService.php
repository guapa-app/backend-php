<?php

namespace App\Services;

use App\Contracts\Repositories\DatabaseNotificationRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class DatabaseNotificationService
{
    private $databaseNotificationRepository;

    public function __construct(DatabaseNotificationRepositoryInterface $databaseNotificationRepository)
    {
        $this->databaseNotificationRepository = $databaseNotificationRepository;
    }

    public function create(array $data): Model
    {
        return $this->databaseNotificationRepository->create($data);
    }
}
