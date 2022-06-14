<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Events\ProductCreated;
use App\Models\Product;
use Carbon\Carbon;

/**
 * Product service
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
	 * Create new product with relations
	 * @param  array  $data
	 * @return \App\Models\Product
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
	 * Update product media
	 * @param  \App\Models\Product $product
	 * @param  array 			   $data
	 * @return \App\Models\Product
	 */
	public function updateMedia(Product $product, array $data): Product
	{
		// New media must be specified or old media to keep
		// without deletion
		if ( ! isset($data['media']) && ! isset($data['keep_media'])) {
			return $product;
		}

		// If no keep_media array is provided
		// We will remove all old media
		$keep_media = [0];
		if (isset($data['keep_media']) && ! empty($data['keep_media'])) {
			$keep_media = $data['keep_media'];
		}

		// Remove media user doesn't want to keep
		$product->media()->whereNotIn('id', $keep_media)->delete();

		// Check for new media
		if ( ! isset($data['media']) || ! is_array($data['media'])) {
			return $product;
		}
		
		foreach ($data['media'] as $key => $value) {
			if ($value instanceof UploadedFile) {
				$product->addMedia($value)->toMediaCollection('products');
			}
		}

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
		if (isset($data['address_ids']) && ! empty($data['address_ids'])) {
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

        // Check if the owner of Product is the logged in user
        if ( ! auth()->user()->can('delete', $product)) {
            abort(403, "You can't delete this product");
        }

        $this->productRepository->delete($productId);
	}
}
