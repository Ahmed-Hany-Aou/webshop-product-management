<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\RateLimiter;

class ProductApiRateLimitTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $token;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create an admin user
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->token = $this->admin->createToken('TestToken')->plainTextToken;
        
        // Create a test product
        $this->product = Product::factory()->create();
        
        // Clear any existing rate limiters
        RateLimiter::clear('api:' . $this->admin->id);
    }

    /**
     * Test that a user can make up to 10 requests per minute.
     */
    public function test_user_can_make_requests_within_rate_limit()
    {
        // Make 10 requests (within the limit)
        for ($i = 0; $i < 10; $i++) {
            $response = $this->getJson('/api/products', [
                'Authorization' => 'Bearer ' . $this->token,
            ]);
            
            $response->assertStatus(200);
        }
    }

    /**
     * Test that the 11th request within a minute returns a 429 Too Many Requests.
     */
    public function test_exceeding_rate_limit_returns_429()
    {
        // Make 10 requests (within the limit)
        for ($i = 0; $i < 10; $i++) {
            $this->getJson('/api/products', [
                'Authorization' => 'Bearer ' . $this->token,
            ]);
        }
        
        // The 11th request should be rate limited
        $response = $this->getJson('/api/products', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);
        
        $response->assertStatus(429); // Too Many Requests
    }

    /**
     * Test that different users have separate rate limits.
     */
    

    /**
     * Test that unauthenticated requests are also rate limited.
     */
  
    /**
     * Test that rate limits reset after the time window expires.
     * 
     * Note: This test is commented out because it would require waiting for
     * the rate limit window to expire, which would make the test slow.
     * In a real scenario, you might mock the time or use a shorter window.
     */
    
    public function test_rate_limits_reset_after_time_window()
    {
        // Make 10 requests (within the limit)
        for ($i = 0; $i < 10; $i++) {
            $this->getJson('/api/products', [
                'Authorization' => 'Bearer ' . $this->token,
            ]);
        }
        
        // The 11th request should be rate limited
        $response = $this->getJson('/api/products', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);
        $response->assertStatus(429);
        
        // Wait for the rate limit window to expire (60 seconds)
        sleep(61);
        
        // After the window expires, we should be able to make requests again
        $response = $this->getJson('/api/products', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);
        $response->assertStatus(200);
    }

}
