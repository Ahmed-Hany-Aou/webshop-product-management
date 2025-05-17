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
        // Define the thresholds and adjustment percentages
        $lowStockThreshold = 10;
        $highStockThreshold = 100;
        $increasePercentage = 0.10; // Increase price by 10% for low stock
        $decreasePercentage = 0.05; // Decrease price by 5% for high stock

        // Adjust price based on stock levels
        if ($product->stock_quantity <= $lowStockThreshold) {
            // Low stock: Increase price by X%
            $product->price += $product->price * $increasePercentage;  /// if products 10 or less increase price by 10%
        } elseif ($product->stock_quantity >= $highStockThreshold) {  /// if products 100 or more decrease price by 5%
            // High stock: Decrease price by Y%
            $product->price -= $product->price * $decreasePercentage;
        }

        // Return the updated product with adjusted price
        return $product;
    }
}
