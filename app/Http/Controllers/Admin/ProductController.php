<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\BaseAdminController;
use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use App\Contracts\Repositories\ProductRepositoryInterface;

class ProductController extends BaseAdminController
{
    private $productService;
    private $productRepository;

    public function __construct(ProductService $productService, ProductRepositoryInterface $productRepository)
    {
        parent::__construct();

        $this->productService = $productService;
        $this->productRepository = $productRepository;
    }

	public function index(Request $request)
	{
        $products = $this->productRepository->all($request);
        return response()->json($products);
	}

	public function single($id)
	{
		$product = $this->productService->getOne($id);
        return response()->json($product);
	}

	public function create(ProductRequest $request)
	{
        $data = $request->validated();
        $product = $this->productService->create($data);
        return response()->json($product);
	}

    public function update(ProductRequest $request, $id = 0)
    {
        $product = $this->productService->update($id, $request->validated());
        return response()->json($product);
    }

    public function delete($id = 0)
    {
        $this->productRepository->delete($id);
        return response()->json([
            'message' => $id,
        ]);
    }
}
