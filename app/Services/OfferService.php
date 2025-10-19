<?php

namespace App\Services;

use App\Contracts\Repositories\OfferRepositoryInterface;
use App\Events\OfferCreated;
use App\Models\Offer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class OfferService
{
    private $repository;

    public function __construct(OfferRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function create(array $data): Offer
    {
        // Check if current user can create offer for provided product
        $this->check($data['product_id']);

        // Each product should have only one offer
        // Delete old offer for provided product
        $this->repository->delete([], ['product_id' => $data['product_id']]);

        // Create new offer
        $offer = $this->repository->create($data);

        $imageData = Arr::only($data, ['image']);
        $this->updateImage($offer, $imageData);
//        event(new OfferCreated($offer));

        return $offer;
    }

    public function update($offer, array $data): Offer
    {
        $this->check($data['product_id']);

        $offer = $this->repository->update($offer, $data);

        $imageData = Arr::only($data, ['image']);
        $this->updateImage($offer, $imageData);

        return $offer;
    }

    public function delete(int $id)
    {
        $offer = $this->repository->getOneOrFail($id);
        $this->check($offer->product_id);
        $this->repository->delete($id);
    }

    public function updateImage(Offer $offer, array $data): Offer
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $offer->addMedia($data['image'])->toMediaCollection('offer_images');
        } elseif ($this->repository->isAdmin() && !isset($data['image'])) {
            // Delete all offer media
            // As there is only one collection - offer_images
            $offer->media()->delete();
        }

        return $offer;
    }

    public function check($productId)
    {
        // Check if current user can manage provided product
        $user = auth()->user();
        if ($user->isAdmin()) {
            return true;
        }

        $count = \DB::select("
			SELECT COUNT(*) as user_count FROM user_vendor WHERE user_id={$user->id}
			AND vendor_id = (SELECT vendor_id FROM products WHERE products.id = {$productId})
		");

        if ($count[0]->user_count < 1) {
            abort(403, 'You cannot manage offers for this product');
        }

        return true;
    }
}
