<?php

namespace App\Services;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Events\ProductCreated;
use App\Models\Product;

/**
 * Product service.
 */
class ProductService
{
    private $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getOne(int $id, $where = [])
    {
        return $this->productRepository->getOneWithRelations($id, $where);
    }

    /**
     * Create new product with relations.
     * @param array $data
     * @return Product
     */
    public function create(array $data): Product
    {
        // Create product
        $product = $this->productRepository->create($data);

        // Update product media only if media params are provided
        if (isset($data['media'])) {
            $this->updateMedia($product, $data);
        }

        if (isset($data['category_ids'])) {
            $this->updateCategories($product, $data['category_ids']);
        }

        $this->updateAddresses($product, $data);

        event(new ProductCreated($product));

        return $product;
    }

    public function update($id, array $data): Product
    {
        $product = $this->productRepository->update($id, $data);

        // Update product media only if media params are provided
        if (isset($data['media']) || isset($data['keep_media'])) {
            $this->updateMedia($product, $data);
        }

        if (isset($data['category_ids'])) {
            $this->updateCategories($product, $data['category_ids']);
        }

        $this->updateAddresses($product, $data);

        return $product;
    }

    /**
     * Update product media.
     * @param Product $product
     * @param array $data
     * @return Product
     */
    public function updateMedia(Product $product, array $data): Product
    {
        (new MediaService())->handleMedia($product, $data);

        $product->load('media');

        return $product;
    }

    public function updateCategories(Product $product, array $categories): Product
    {
        $product->setTaxonomies($categories, 'specialty');
        $product->load('categories');

        return $product;
    }

    public function updateAddresses(Product $product, array $data): Product
    {
        if (!empty($data['address_ids'])) {
            $product->addresses()->sync($data['address_ids']);
        } else {
            $product->addresses()->detach();
        }

        $product->load('addresses');

        return $product;
    }

    public function delete(int $productId)
    {
        $product = $this->productRepository->getOneOrFail($productId);

        // Check if the owner of Product is the logged-in user
        if (!auth()->user()->can('delete', $product)) {
            abort(403, "You can't delete this product");
        }

        $this->productRepository->delete($productId);
    }
}
