<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\PriceAdjustmentService; // Add the PriceAdjustmentService

class ProductService implements ProductServiceInterface
{
    protected $priceAdjustmentService;

    public function __construct(PriceAdjustmentService $priceAdjustmentService)
    {
        $this->priceAdjustmentService = $priceAdjustmentService; // Inject the PriceAdjustmentService
    }

    /**
     * Get all products
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllProducts()
    {
        return Product::all();
    }

    /**
     * Get paginated products
     *
     * @param int $page
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginatedProducts($page = 1, $perPage = 10)
    {
        return Product::paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Create a new product
     *
     * @param array $data
     * @return Product
     */
    public function createProduct(array $data)
    {
        // Create the product
        $product = Product::create($data);

        // Adjust the price based on stock
        $product = $this->priceAdjustmentService->adjustPrice($product);

        // Save the adjusted price
        $product->save();

        return $product;
    }

    /**
     * Get a product by ID
     *
     * @param int $id
     * @return Product|null
     */
    public function getProductById($id)
    {
        return Product::find($id);
    }

    /**
     * Update a product
     *
     * @param int $id
     * @param array $data
     * @return Product|null
     */
    public function updateProduct($id, array $data)
    {
        $product = $this->getProductById($id);

        if (!$product) {
            return null;
        }

        // Update the product data
        $product->update($data);

        // Adjust the price after update
        $product = $this->priceAdjustmentService->adjustPrice($product);

        // Save the adjusted price
        $product->save();

        return $product;
    }

    /**
     * Delete a product
     *
     * @param int $id
     * @return bool
     */
    public function deleteProduct($id)
    {
        $product = $this->getProductById($id);

        if (!$product) {
            return false;
        }

        return $product->delete();
    }
}
