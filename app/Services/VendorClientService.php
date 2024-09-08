<?php

namespace App\Services;

use App\Enums\ProductType;
use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorClient;
use App\Notifications\AddVendorClientNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class VendorClientService
{
    public function addClient(Vendor $vendor, array $data)
    {
        return DB::transaction(function () use ($vendor, $data) {
            $user = $this->findOrCreateUser($data);
            $isNewClient = $user->wasRecentlyCreated;

            $this->sendNotification($user, $vendor, $isNewClient);

            $vendorClient = VendorClient::firstOrCreate([
                'vendor_id' => $vendor->id,
                'user_id' => $user->id,
            ]);

            $orders_count = $this->getUserOrdersCount($user, $vendor, $isNewClient);

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'orders_count' => $orders_count,
            ];
        });
    }

    private function findOrCreateUser(array $data): User
    {
        return User::firstOrCreate(
            ['phone' => $data['phone']],
            ['name' => $data['name'], 'status' => User::STATUS_ACTIVE]
        );
    }

    private function getUserOrdersCount(User $user, Vendor $vendor, bool $isNewClient): int
    {
        if ($isNewClient) {
            return 0;
        }

        $user->load(['orders' => function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        }]);

        return $user->orders->count();
    }

    public function listClientsWithOrderCount(Vendor $vendor, array $filters = []): Collection
    {
        $query = $vendor->clients()
            ->with(['user' => function ($query) use ($vendor) {
                $query->select('id', 'name', 'email', 'phone')
                    ->withCount(['orders' => function ($query) use ($vendor) {
                        $query->where('vendor_id', $vendor->id);
                    }]);
            }]);
        $this->applyFilters($query, $filters);

        return $query->get()->map(function ($client) {
            return [
                'id' => $client->user->id,
                'name' => $client->user->name,
                'email' => $client->user->email,
                'phone' => $client->user->phone,
                'orders_count' => $client->user->orders_count,
            ];
        });
    }

    private function applyFilters($query, array $filters): void
    {
        if (!empty($filters['search'])) {
            $query->whereHas('user', function ($query) use ($filters) {
                $search = '%' . $filters['search'] . '%';
                $query->where('name', 'like', $search)
                    ->orWhere('phone', 'like', $search);
            });
        }
    }

    public function getClientOrders($vendor_id, $client_id, ?ProductType $productType = null): Collection
    {
        $query = Order::where('vendor_id', $vendor_id)
            ->where('user_id', $client_id);
        if ($productType) {
            $query->hasProductType($productType);
        }

        return $query->get();
    }

    public function deleteClient(Vendor $vendor, $clientId)
    {
        return VendorClient::where('vendor_id', $vendor->id)
            ->where('user_id', $clientId)
            ->delete();
    }

    private function createNewUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    private function sendNotification(User $user, Vendor $vendor, $isNewClient = false)
    {
//        Notification::send($user, new AddVendorClientNotification($vendor, $isNewClient));
    }
}
