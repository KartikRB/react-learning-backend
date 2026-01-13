<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class productController extends Controller
{
    public ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();
        if ($request->hasFile('primary_image')) {
            $validated['primary_image'] = $request->file('primary_image')->store('products', 'public');
        }
        $product = $this->productService->create($validated);
        return response()->json([
            'status' => true,
            'message' => 'Product created successfully!',
            'data' => $product
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();
        if ($request->hasFile('primary_image')) {
            $validated['primary_image'] = $request->file('primary_image')->store('products', 'public');
        }
        $product = $this->productService->update($product, $validated);
        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully!',
            'data' => $product
        ]);
    }

    public function destroy(Product $product)
    {
        $this->productService->delete($product);
        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully!',
        ]);
    }

    public function show($id)
    {
        $product = $this->productService->getProduct($id);
        return response()->json([
            'status' => true,
            'message' => 'Product data found successfully!',
            'data' => $product
        ]);
    }

    public function getProducts()
    {
        $data = $this->productService->with('images', 'category', 'primaryImage')->get();

        return response()->json([
            'status' => true,
            'message' => 'Product data found!',
            'data' => $data
        ]);
    }

    public function removeProductImage($id)
    {
        $removed = $this->productService->removeProductImage($id);

        if(!$removed){
            return response()->json([
                'status' => false,
                'message' => 'Product image not removed!',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product image removed successfully!',
        ]);
    }

    public function updateProductStatus(Request $request, $id)
    {
        $product = $this->productService->getProduct($id);
        $is_active = $request->is_active;
        $this->productService->update($product, ['is_active' => $is_active]);

        return response()->json([
            'status' => true,
            'message' => 'product status updated successfully!',
            'is_active' => $is_active
        ]);
    }

    public function updateProductFeatured(Request $request, $id)
    {
        $product = $this->productService->getProduct($id);
        $is_featured = $request->is_featured;
        $this->productService->update($product, ['is_featured' => $is_featured]);

        return response()->json([
            'status' => true,
            'message' => 'product featured status updated successfully!',
            'is_featured' => $is_featured
        ]);
    }
}
