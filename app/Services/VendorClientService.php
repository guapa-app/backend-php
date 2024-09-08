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
    public function addClient(array $data)
    {
        return DB::transaction(function () use ( $data) {
            $vendor = $this->getVendor();
            $user = $this->findOrCreateUser($data);
            $isNewClient = $user->wasRecentlyCreated;

            $this->sendNotification($user, $vendor, $isNewClient);

            $vendorClient = VendorClient::firstOrCreate([
                'vendor_id' => $vendor->id,
                'user_id' => $user->id,
            ]);

            $orders_count = $this->getUserOrdersCount($user, $isNewClient);

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

    private function getUserOrdersCount(User $user, bool $isNewClient): int
    {
        if ($isNewClient) {
            return 0;
        }
        $vendor = $this->getVendor();

        $user->load(['orders' => function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        }]);

        return $user->orders->count();
    }

    public function listClientsWithOrderCount(array $filters = []): Collection
    {
        $vendor = $this->getVendor();
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

    public function getClientOrders($client_id, ?ProductType $productType = null): Collection
    {
        $vendor = $this->getVendor();
        $query = Order::where('vendor_id', $vendor->id)
            ->where('user_id', $client_id);
        if ($productType) {
            $query->hasProductType($productType);
        }

        return $query->get();
    }

    public function deleteClient($clientId)
    {
        $vendor = $this->getVendor();
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
    private function getVendor(): Vendor
    {
        return auth()->user()->userVendor?->vendor;
    }
}
