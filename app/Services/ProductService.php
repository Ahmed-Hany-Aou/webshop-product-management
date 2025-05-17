<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService implements ProductServiceInterface
{
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
        return Product::create($data);
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
        
        $product->update($data);
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
