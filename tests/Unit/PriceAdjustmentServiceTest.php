<?php
namespace Tests\Unit;

use App\Models\Product;
use App\Services\PriceAdjustmentService;
use Tests\TestCase; // Changed from PHPUnit\Framework\TestCase
use Illuminate\Foundation\Testing\RefreshDatabase;

class PriceAdjustmentServiceTest extends TestCase // Now extends Laravel's TestCase
{
    use RefreshDatabase; // Added to ensure clean database state

    protected $priceAdjustmentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->priceAdjustmentService = new PriceAdjustmentService();
    }

    public function test_adjust_price_for_stock_below_low_threshold()
    {
        // Stock below low threshold (e.g., 5)
        $product = $this->createTestProduct(100, 5);
        $adjustedProduct = $this->priceAdjustmentService->adjustPrice($product);

        // Should increase by 10%
        $this->assertEquals(110, $adjustedProduct->price);
    }

    public function test_adjust_price_for_stock_above_high_threshold()
    {
        // Stock above high threshold (e.g., 150)
        $product = $this->createTestProduct(200, 150);
        $adjustedProduct = $this->priceAdjustmentService->adjustPrice($product);

        // Should decrease by 5%
        $this->assertEquals(190, $adjustedProduct->price);
    }

    public function test_adjust_price_for_stock_between_thresholds()
    {
        // Stock between thresholds (e.g., 50)
        $product = $this->createTestProduct(75, 50);
        $adjustedProduct = $this->priceAdjustmentService->adjustPrice($product);

        // Should remain unchanged
        $this->assertEquals(75, $adjustedProduct->price);
    }

    public function test_adjust_price_for_stock_at_low_threshold()
    {
        // Stock exactly at low threshold (10)
        $product = $this->createTestProduct(90, 10);
        $adjustedProduct = $this->priceAdjustmentService->adjustPrice($product);

        // Should increase by 10%
        $this->assertEquals(99, $adjustedProduct->price);
    }

    public function test_adjust_price_for_stock_at_high_threshold()
    {
        // Stock exactly at high threshold (100)
        $product = $this->createTestProduct(400, 100);
        $adjustedProduct = $this->priceAdjustmentService->adjustPrice($product);

        // Should decrease by 5%
        $this->assertEquals(380, $adjustedProduct->price);
    }

    public function test_adjust_price_for_stock_at_low_threshold_as_string()
    {
        // Stock at low threshold as string
        $product = $this->createTestProduct(90, '10');
        $adjustedProduct = $this->priceAdjustmentService->adjustPrice($product);

        // Should increase by 10%
        $this->assertEquals(99, $adjustedProduct->price);
    }

    public function test_adjust_price_for_stock_at_high_threshold_as_string()
    {
        // Stock at high threshold as string
        $product = $this->createTestProduct(400, '100');
        $adjustedProduct = $this->priceAdjustmentService->adjustPrice($product);

        // Should decrease by 5%
        $this->assertEquals(380, $adjustedProduct->price);
    }

   
    // Additional edge cases

    public function test_adjust_price_for_zero_stock()
    {
        // Zero stock (edge case)
        $product = $this->createTestProduct(100, 0);
        $adjustedProduct = $this->priceAdjustmentService->adjustPrice($product);

        // Should increase by 10% (as it's below low threshold)
        $this->assertEquals(110, $adjustedProduct->price);
    }

    public function test_adjust_price_for_negative_stock()
    {
        // Negative stock (invalid case, but should be handled)
        $product = $this->createTestProduct(100, -5);
        $adjustedProduct = $this->priceAdjustmentService->adjustPrice($product);

        // Should increase by 10% (as it's below low threshold)
        $this->assertEquals(110, $adjustedProduct->price);
    }

    public function test_adjust_price_for_extremely_high_stock()
    {
        // Extremely high stock
        $product = $this->createTestProduct(100, 10000);
        $adjustedProduct = $this->priceAdjustmentService->adjustPrice($product);

        // Should decrease by 5% (as it's above high threshold)
        $this->assertEquals(95, $adjustedProduct->price);
    }

    public function test_adjust_price_for_zero_price()
    {
        // Zero price (edge case)
        $product = $this->createTestProduct(0, 5);
        $adjustedProduct = $this->priceAdjustmentService->adjustPrice($product);

        // Should remain 0 (10% of 0 is still 0)
        $this->assertEquals(0, $adjustedProduct->price);
    }

    

    /**
     * Helper method to create a real Product with given price and stock
     */
    private function createTestProduct($price, $stock)
    {
        // Create a real Product instance using the factory
        return Product::factory()->create([
            'price' => $price,
            'stock_quantity' => $stock,
        ]);
    }
}
