<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase; // This trait will reset the database after each test

    /**
     * Test the GET /api/products route to ensure all products are returned.
     *
     * @return void
     */


    public function test_index_withData_returnsProducts()  //More Explicit: test_index_withData_returnsProducts()
    {
        // Create a product in the database
        Product::factory()->create();

        // Create a user and login (get the Bearer token)
        $user = User::factory()->create();
        $token = $user->createToken('YourAppName')->plainTextToken; // Get the API token

        // Make a GET request to the /api/products route with the Bearer token
        $response = $this->get('/api/products', [
            'Authorization' => 'Bearer ' . $token,  // Pass the Bearer token in the request
         //    'Accept' => 'application/json',         // Ensure the response is JSON --- no need for it test will also pass without it
        ]);

        // Assert that the status code is 200 (OK)
        $response->assertStatus(200);

       // Assert that the response contains the correct JSON structure--- now this return the exact same thing as testing with post man on real db
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'description', 'price', 'stock_quantity','created_at',"updated_at"]  // no need to return all data as postman-- main thing is to return same columns name as our db-- dont forget we testing on web_shop test db and you can return with "" or with ''
        ]);
    }
}
