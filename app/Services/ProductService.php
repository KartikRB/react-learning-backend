<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use App\Repositories\ProductRepository;

class ProductService extends BaseService
{
    protected string $repositoryName = ProductRepository::class;

    public function create(array $data): Product
    {
        $primaryImage = $data['primary_image'] ?? null;
        unset($data['primary_image']);

        $product = $this->repository->create($data);

        if ($primaryImage) {
            $this->updateOrCreateProductImage($product->id, $primaryImage);
        }

        return $product->refresh();
    }

    public function update(Product $product, array $data): Product
    {
        $primaryImage = $data['primary_image'] ?? null;
        unset($data['primary_image']);

        $product->update($data);

        if ($primaryImage) {
            $this->updateOrCreateProductImage($product->id, $primaryImage);
        }

        return $product->refresh();
    }

    public function getProduct($id)
    {
        return $this->repository->with('images', 'category', 'primaryImage')->find($id);
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    public function removeProductImage($id)
    {
        $product = $this->getProduct($id);

        if (!$product || !$product->productDetail) {
            return false;
        }

        $product->productDetail()->update(['primary_image' => '']);

        return true;
    }

    public function updateOrCreateProductImage($product_id, $imagePath)
    {
        ProductImage::updateOrCreate([
            'product_id' => $product_id,
            'is_primary' => 1,
        ],[
            'order' => 1,
            'path' => $imagePath
        ]);
    }

    public function uploadProductImages(Product $product, $images)
    {
        foreach ($images as $image) {
            $path = $image->store('products', 'public');
            $product->images()->create(['path' => $path]);
        }

        return true;
    }

}
