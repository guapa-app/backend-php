<?php

namespace App\Services;

use App\Enums\ProductType;
use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorClient;
use App\Notifications\AddedAsVendorClient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Twilio\Rest\Client as TwilioClient;

class VendorClientService
{

    public function addClient(Vendor $vendor, array $data)
    {
        return DB::transaction(function () use ($vendor, $data) {
            $user = User::where('phone', $data['phone'])->first();

            if (!$user) {
                $user = $this->createNewUser($data);
//                $this->sendWhatsAppMessage($user);
            } else {
                $this->sendFcmNotification($user, $vendor);
            }

            $vendorClient = VendorClient::firstOrCreate([
                'vendor_id' => $vendor->id,
                'user_id' => $user->id,
            ]);

            return [
                'user' => $user,
                'vendor_client' => $vendorClient,
            ];
        });
    }

    public function listClientsWithOrderCount(Vendor $vendor, array $filters = []): Collection
    {
        $query = $vendor->clients()
            ->with(['user' => function ($query) use ($vendor){
                $query->select('id', 'name', 'email', 'phone')
                    ->with(['orders' => function ($query) use ($vendor) {
                    $query->where('vendor_id', $vendor->id);
                }]);
            }]);

        $this->applyFilters($query, $filters);


        return $query->get();
    }
    private function applyFilters( $query, array $filters): void
    {
        if (!empty($filters['name']) || !empty($filters['phone'])) {
            $query->whereHas('user', function ($query) use ($filters) {
                if (!empty($filters['name'])) {
                    $query->where('name', 'like', '%' . $filters['name'] . '%');
                }
                if (!empty($filters['phone'])) {
                    $query->where('phone', 'like', '%' . $filters['phone'] . '%');
                }
            });
        }
    }
    public function getClientOrders($vendor_id,$client_id, ?ProductType $productType = null): Collection
    {
        $query = Order::where('vendor_id', $vendor_id)
            ->where('user_id',$client_id);

        if ($productType) {
            $query->hasProductType($productType);
        }

        return $query->get();
    }


    public function deleteClient(Vendor $vendor, $clientId)
    {
        $client = $this->getClient($vendor, $clientId);
        return VendorClient::where('vendor_id', $vendor->id)
            ->where('user_id', $client->id)
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

    private function sendFcmNotification(User $user, Vendor $vendor)
    {
//        Notification::send($user, new AddedAsVendorClient($vendor));
    }

    private function sendWhatsAppMessage(User $user)
    {

    }
}
