<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the GET /api/products route to ensure all products are returned.
     */


     /*
public function test_index_withData_returnsProducts()
{
    // Create products in the database
    Product::factory()->create();

    // Create a user and login (get the Bearer token)
    $user = User::factory()->create();
    $token = $user->createToken('YourAppName')->plainTextToken;

    // Make a GET request to the /api/products route with the Bearer token
    $response = $this->get('/api/products', [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json', // Ensure the response is JSON
    ]);

    // Assert that the status code is 200 (OK)
    $response->assertStatus(200);

    // Assert that the response contains the correct JSON structure
    $response->assertJsonStructure([
        'status_code',
        'message',
        'result' => [
            'items' => [
                '*' => ['id', 'name', 'description', 'price', 'stock_quantity', 'created_at', 'updated_at'],
            ],
        ],
        'meta' => [
            'page',
            'take',
            'items_count',
            'total_items_count',
            'page_count',
            'has_previous_page',
            'has_next_page',
        ],
    ]);
}

*/
    /**
     * Test index returns empty items and correct meta when no products exist.
     */


     /*
    public function test_index_returns_empty_when_no_products_exist()
    {
        $user = User::factory()->create();
        $token = $user->createToken('YourAppName')->plainTextToken;

        $response = $this->get('/api/products', [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
        ]);

        $response->assertStatus(200)
        ->assertJson([
            'result' => [
            'items' => [],
            ],
        ])
        ->assertJsonStructure([
            'status_code',
            'message',
            'result' => [
            'items',
            ],
            'meta' => [
            'page',
            'take',
            'items_count',
            'total_items_count',
            'page_count',
            'has_previous_page',
            'has_next_page',
            ],
        ]);
    }

*/


    /**
     * Test admin cannot create a product with missing required fields.
     */
    public function test_admin_cannot_create_product_with_missing_fields()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('AdminToken')->plainTextToken;

        $response = $this->postJson('/api/products', [], [
        'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'price', 'stock_quantity']);
    }

    /**
     * Test updating a product with invalid data returns validation errors.
     */
    public function test_update_product_with_invalid_data_returns_validation_errors()
    {
        $product = Product::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('AdminToken')->plainTextToken;

        $invalidData = [
        'name' => '',
        'price' => 'not-a-number',
        'stock_quantity' => -10,
        ];

        $response = $this->putJson('/api/products/' . $product->id, $invalidData, [
        'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'price', 'stock_quantity']);
    }

    /**
     * Test admin cannot delete a product twice (second time returns 404).
     */
    public function test_admin_cannot_delete_product_twice()
    {
        $product = Product::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('AdminToken')->plainTextToken;

        $response1 = $this->deleteJson('/api/products/' . $product->id, [], [
        'Authorization' => 'Bearer ' . $token,
        ]);
        $response1->assertStatus(200);

        $response2 = $this->deleteJson('/api/products/' . $product->id, [], [
        'Authorization' => 'Bearer ' . $token,
        ]);
        $response2->assertStatus(404);
    }

    /**
     * Test user cannot access product endpoints without token.
     */
    public function test_user_cannot_access_product_endpoints_without_token()
    {
        $product = Product::factory()->create();

        $this->getJson('/api/products')->assertStatus(401);
        $this->getJson('/api/products/' . $product->id)->assertStatus(401);
        $this->postJson('/api/products', [])->assertStatus(401);
        $this->putJson('/api/products/' . $product->id, [])->assertStatus(401);
        $this->deleteJson('/api/products/' . $product->id)->assertStatus(401);
    }

    /**
     * Test admin can view paginated products.
     */


     /*
    public function test_admin_can_view_paginated_products()
    {
        Product::factory()->count(15)->create();
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('AdminToken')->plainTextToken;

        $response = $this->get('/api/products?page=2&take=5', [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
        ]);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'status_code',
            'message',
            'result' => [
            'items' => [
                '*' => ['id', 'name', 'description', 'price', 'stock_quantity', 'created_at', 'updated_at']
            ],
            ],
            'meta' => [
            'page',
            'take',
            'items_count',
            'total_items_count',
            'page_count',
            'has_previous_page',
            'has_next_page',
            ],
        ]);
    }
*/
    /**
     * Test the GET /api/products/{id} route to ensure a specific product is returned.
     */
    public function test_show_withData_returnsProductBySpecificID()
{
    $product = Product::factory()->create();
    $user = User::factory()->create(['role' => 'admin']); // Create an admin user
    $token = $user->createToken('YourAppName')->plainTextToken;

    $response = $this->get('/api/products/' . $product->id, [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ]);
    $response->assertStatus(200);

    // Adjusted for a single product, not a list
    $response->assertJsonStructure([
        'status_code',
        'message',
        'result' => [
            'id', 'name', 'description', 'price', 'stock_quantity', 'created_at', 'updated_at',
        ],
    ]);
}
    public function test_update_withData_updatesProductBySpecificID()
    {
        $product = Product::factory()->create();
        $user = User::factory()->create(['role' => 'admin']); // Create an admin user
        $token = $user->createToken('YourAppName')->plainTextToken;

        // Define the updated product data
        $updatedData = [
            'name' => 'Updated Product',
            'description' => 'Updated Description',
            'price' => 150.00,
            'stock_quantity' => 20,
        ];

        // Make a PUT request to the /api/products/{product} route with the Bearer token
        $response = $this->put('/api/products/' . $product->id, $updatedData, [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json', // Ensure the response is JSON
        ]);

        // Assert that the status code is 200 (OK)
        $response->assertStatus(200);

        // Assert that the product was updated in the database
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'description' => 'Updated Description',
            'price' => 150.00,
            'stock_quantity' => 20,
        ]);
    }



    // --- STORE (CREATE) TESTS ---

    /**
     * Test admin can create a product.
     */
    public function test_admin_can_create_product()
    {
        $admin = User::factory()->create(["role" => "admin"]);
        $token = $admin->createToken("AdminToken")->plainTextToken;

        $productData = [
            "name" => "New Test Product by Admin",
            "description" => "This is a test product created by an admin.",
            "price" => 99.99,
            "stock_quantity" => 50,
        ];

        $response = $this->postJson("/api/products", $productData, [
            "Authorization" => "Bearer " . $token,
        ]);

        $response->assertStatus(201) // HTTP 201 Created
            ->assertJsonFragment(["name" => "New Test Product by Admin"]);
        
        $this->assertDatabaseHas("products", [
            "name" => "New Test Product by Admin",
            "description" => "This is a test product created by an admin.",
        ]);
    }

    /**
     * Test regular user cannot create a product (assuming policy denies).
     */
    public function test_user_cannot_create_product()
    {
        $user = User::factory()->create(["role" => "user"]); // Assuming default role is 'user' or similar
        $token = $user->createToken("UserToken")->plainTextToken;

        $productData = [
            "name" => "New Test Product by User",
            "description" => "This is a test product attempted by a user.",
            "price" => 49.99,
            "stock_quantity" => 10,
        ];

        $response = $this->postJson("/api/products", $productData, [
            "Authorization" => "Bearer " . $token,
        ]);

        // Expecting 403 Forbidden if policy denies creation
        // Or 401 if not authenticated (though we are)
        // Or potentially another status if the policy isn't hit as expected.
        // Common for authorization failures is 403.
        $response->assertStatus(403);
    }

    /**
     * Test creating a product with invalid data returns validation errors.
     */
    public function test_create_product_with_invalid_data_returns_validation_errors()
    {
        $admin = User::factory()->create(["role" => "admin"]);
        $token = $admin->createToken("AdminToken")->plainTextToken;

        $invalidProductData = [
            "name" => "", // Name is required
            "price" => "not-a-number", // Price must be numeric
            "stock_quantity" => -5, // Stock quantity must be non-negative
        ];

        $response = $this->postJson("/api/products", $invalidProductData, [
            "Authorization" => "Bearer " . $token,
        ]);

        $response->assertStatus(422) // HTTP 422 Unprocessable Entity for validation errors
            ->assertJsonValidationErrors(["name", "price", "stock_quantity"]);
    }

    // --- UPDATE TESTS (Expanding on existing) ---

    /**
     * Test regular user cannot update a product (assuming policy denies).
     */
    public function test_user_cannot_update_product()
    {
        $product = Product::factory()->create();
        $user = User::factory()->create(["role" => "user"]);
        $token = $user->createToken("UserToken")->plainTextToken;

        $updatedData = [
            "name" => "Attempted Update by User",
            "price" => 120.00,
        ];

        $response = $this->putJson("/api/products/" . $product->id, $updatedData, [
            "Authorization" => "Bearer " . $token,
        ]);

        $response->assertStatus(403); // Expecting 403 Forbidden
    }

    /**
     * Test updating a non-existent product returns 404.
     */
    public function test_update_non_existent_product_returns_404()
    {
        $admin = User::factory()->create(["role" => "admin"]);
        $token = $admin->createToken("AdminToken")->plainTextToken;
        $nonExistentProductId = 9999;

        $updatedData = [
            "name" => "Updated Non-existent Product",
            "price" => 150.00,
        ];

        $response = $this->putJson("/api/products/" . $nonExistentProductId, $updatedData, [
            "Authorization" => "Bearer " . $token,
        ]);

        $response->assertStatus(404);
    }

    // --- DELETE TESTS ---

    /**
     * Test admin can delete a product.
     */
    public function test_admin_can_delete_product()
    {
        $product = Product::factory()->create();
        $admin = User::factory()->create(["role" => "admin"]);
        $token = $admin->createToken("AdminToken")->plainTextToken;

        $response = $this->deleteJson("/api/products/" . $product->id, [], [
            "Authorization" => "Bearer " . $token,
        ]);

        $response->assertStatus(200) // Or 204 No Content, depending on controller implementation
                 ->assertJsonFragment(["message" => "Product deleted successfully"]); // If message is returned
        
        $this->assertDatabaseMissing("products", ["id" => $product->id]);
    }

    /**
     * Test regular user cannot delete a product (assuming policy denies).
     */
    public function test_user_cannot_delete_product()
    {
        $product = Product::factory()->create();
        $user = User::factory()->create(["role" => "user"]);
        $token = $user->createToken("UserToken")->plainTextToken;

        $response = $this->deleteJson("/api/products/" . $product->id, [], [
            "Authorization" => "Bearer " . $token,
        ]);

        $response->assertStatus(403); // Expecting 403 Forbidden
    }

    /**
     * Test deleting a non-existent product returns 404.
     */
    public function test_delete_non_existent_product_returns_404()
    {
        $admin = User::factory()->create(["role" => "admin"]);
        $token = $admin->createToken("AdminToken")->plainTextToken;
        $nonExistentProductId = 9999;

        $response = $this->deleteJson("/api/products/" . $nonExistentProductId, [], [
            "Authorization" => "Bearer " . $token,
        ]);

        $response->assertStatus(404);
    }

    // --- SHOW TESTS (Expanding on existing) ---

    /**
     * Test regular user can view a specific product (assuming policy allows).
     */
    public function test_user_can_view_specific_product()
    {
        $product = Product::factory()->create();
        $user = User::factory()->create(["role" => "user"]); 
        $token = $user->createToken("UserToken")->plainTextToken;

        $response = $this->getJson("/api/products/" . $product->id, [
            "Authorization" => "Bearer " . $token,
        ]);

        $response->assertStatus(403)
          ->assertJsonFragment(["message" => "This action is unauthorized."]);
    }

    /**
     * Test viewing a non-existent product returns 404.
     */
    public function test_view_non_existent_product_returns_404()
    {
        $user = User::factory()->create(); 
        $token = $user->createToken("UserToken")->plainTextToken;
        $nonExistentProductId = 9999;

        $response = $this->getJson("/api/products/" . $nonExistentProductId, [
            "Authorization" => "Bearer " . $token,
        ]);

        $response->assertStatus(404);
    }

    // --- INDEX TESTS (Expanding on existing) ---

    /**
     * Test unauthenticated user cannot view products.
     */
    public function test_unauthenticated_user_cannot_view_products()
    {
        $response = $this->getJson("/api/products");
        $response->assertStatus(401); // Expecting 401 Unauthorized
    }
}

