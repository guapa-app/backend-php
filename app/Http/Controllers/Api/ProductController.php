<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use App\Contracts\Repositories\ProductRepositoryInterface;

/**
 * @group Products
 *
 */
class ProductController extends BaseApiController
{
	protected $productRepository;
	protected $productService;

	public function __construct(ProductRepositoryInterface $productRepository,
		ProductService $productService)
	{
        parent::__construct();

		$this->productRepository = $productRepository;
		$this->productService = $productService;
	}

    /**
     * Products listing
     *
     * @unauthenticated
     *
     * @responseFile 200 responses/products/list.json
     *
     * @queryParam vendor_id integer Get Products for specific user. Example: 1
     * @queryParam category_ids integer[] Filter products by categories.
     * @queryParam category_ids[].* integer Category id. Example: 1
     * @queryParam type Filter by type (product, service). Example: product
     * @queryParam list_type Filter by list type (default, most_viewed, most_ordered, offers). Example: default
     * @queryParam keyword String to search Products. Example: Dell laptop
     * @queryParam min_price Specify minimum price. Example: 200
     * @queryParam max_price Specify maximum price. Example: 5000
     * @queryParam city_id integer City id. Example: 3
     * @queryParam lat double Latitude. Example: 30.5666
     * @queryParam lng double Longitude. Example: 31.3229
     * @queryParam distance integer Distance in KM used along with lat and lng. Example: 20
     * @queryParam page Page number for pagination Example: 2
     * @queryParam perPage Results to fetch per page Example: 15
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
    	$products = $this->productRepository->all($request);
    	return response()->json($products);
    }

    /**
     * Get Product by id
     *
     * @unauthenticated
     *
     * @responseFile 200 responses/products/details.json
     * @responseFile 404 scenario="Vendor not found" responses/errors/404.json
     *
     * @urlParam id required Product id
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function single($id)
    {
    	$product = $this->productRepository->getOneWithRelations((int) $id);

        $product->description = strip_tags($product->description);

        $product->vendor->description = strip_tags($product->vendor->description);

    	return response()->json($product);
    }

    /**
     * Create product
     *
     * @responseFile 200 responses/products/create.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     *
     * @param  \App\Http\Requests\ProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(ProductRequest $request)
    {
    	$data = $request->validated();
    	$data['user_id'] = $this->user->id;
    	$product = $this->productService->create($data);
    	return response()->json($product);
    }

    /**
     * Update product
     *
     *
     * @urlParam id required Product id
     *
     * @responseFile 200 responses/products/create.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     *
     * @param  int    $id
     * @param  \App\Http\Requests\ProductRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, ProductRequest $request)
    {
        logger("Check update product number - $id",
            [
                'requestall' => $request->all(),
                'data' => $id,
            ]
        );


    	$data = $request->validated();
        logger("Check update order number - $id",
            [
                'requestall' => $request->all(),
                'data' => $data,
            ]
        );


    	$product = $this->productService->update($id, $data);

    	return response()->json($product);
    }

    /**
     * Delete product
     *
     * @responseFile 200 responses/products/delete.json
     * @responseFile 404 scenario="Product not found" responses/errors/404.json
     * @responseFile 403 scenario="Unauthorized" responses/errors/403.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     *
     * @urlParam id required Product id
     *
     * @param  integer $id
     *
     * @return \Illuminat\Http\JsonResponse
     *
     */
    public function delete($id)
    {
        $productId = (int) $id;

        $this->productService->delete($id);

        return response()->json([
            'message' => __('api.product_deleted'),
            'id' => $productId,
        ]);
    }
}
