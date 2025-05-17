<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface ProductServiceInterface
 * 
 * Defines the contract for product service operations
 */
interface ProductServiceInterface
{
    /**
     * Get all products
     *
     * @return Collection
     */
    public function getAllProducts();
    
    /**
     * Get paginated products
     *
     * @param int $page
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedProducts($page = 1, $perPage = 10);
    
    /**
     * Create a new product
     *
     * @param array $data
     * @return Product
     */
    public function createProduct(array $data);
    
    /**
     * Get a product by ID
     *
     * @param int $id
     * @return Product|null
     */
    public function getProductById($id);
    
    /**
     * Update a product
     *
     * @param int $id
     * @param array $data
     * @return Product|null
     */
    public function updateProduct($id, array $data);
    
    /**
     * Delete a product
     *
     * @param int $id
     * @return bool
     */
    public function deleteProduct($id);
}
