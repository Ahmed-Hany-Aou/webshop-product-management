<?php

namespace App\Services;

use App\Models\Product;

/**
 * @OA\Schema(
 *     schema="PriceAdjustmentAlgorithm",
 *     title="Price Adjustment Algorithm",
 *     description="Algorithm that automatically adjusts product prices based on stock levels",
 *     @OA\Property(
 *         property="low_stock_threshold",
 *         type="integer",
 *         description="Stock level below which prices are increased by 10%",
 *         example=10
 *     ),
 *     @OA\Property(
 *         property="high_stock_threshold",
 *         type="integer",
 *         description="Stock level above which prices are decreased by 5%",
 *         example=100
 *     ),
 *     @OA\Property(
 *         property="low_stock_adjustment",
 *         type="number",
 *         format="float",
 *         description="Percentage increase for low stock (0.10 = 10%)",
 *         example=0.10
 *     ),
 *     @OA\Property(
 *         property="high_stock_adjustment",
 *         type="number",
 *         format="float",
 *         description="Percentage decrease for high stock (0.05 = 5%)",
 *         example=0.05
 *     )
 * )
 */
class PriceAdjustmentService
{
    /**
     * Low stock threshold - below this, prices increase by 10%
     */
    protected $lowStockThreshold = 10;
    
    /**
     * High stock threshold - above this, prices decrease by 5%
     */
    protected $highStockThreshold = 100;
    
    /**
     * Adjust product price based on stock quantity
     * 
     * @param Product $product The product to adjust price for
     * @return Product The product with adjusted price
     */
    public function adjustPrice(Product $product)
    {
        $stockQuantity = (int) $product->stock_quantity;
        
        if ($stockQuantity <= $this->lowStockThreshold) {
            // Low stock - increase price by 10%
            $product->price = $product->price * 1.10;
        } elseif ($stockQuantity >= $this->highStockThreshold) {
            // High stock - decrease price by 5%
            $product->price = $product->price * 0.95;
        }
        // Between thresholds - no change
        
        return $product;
    }
}
