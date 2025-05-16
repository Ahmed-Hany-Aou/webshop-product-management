<?php
namespace App\Services;

use App\Models\Product;

class PriceAdjustmentService
{
    /**
     * Adjust the price of a product based on stock levels.
     *
     * @param Product $product
     * @return Product
     */
    public function adjustPrice(Product $product)
    {
        // Validate stock and price before adjusting
        if ($product->stock_quantity === null || $product->stock_quantity < 0) {
            $product->stock_quantity = 0; // Treat negative stock as zero (or handle as needed)
        }

        // Validate price
        if ($product->price === null || $product->price < 0) {
            return $product; // No adjustment for invalid price
        }

        // Define the thresholds and adjustment percentages
        $lowStockThreshold = 10;
        $highStockThreshold = 100;
        $increasePercentage = 0.10; // Increase price by 10% for low stock
        $decreasePercentage = 0.05; // Decrease price by 5% for high stock

        // Adjust price based on stock levels
        if ($product->stock_quantity <= $lowStockThreshold) {
            // Low stock: Increase price by X%
            $product->price += $product->price * $increasePercentage;  // Increase price by 10%
        } elseif ($product->stock_quantity >= $highStockThreshold) { 
            // High stock: Decrease price by Y%
            $product->price -= $product->price * $decreasePercentage;  // Decrease price by 5%
        }

        // Return the updated product with adjusted price
        return $product;
    }
}
