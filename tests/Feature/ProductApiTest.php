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



     //Route::get("/", [ProductController::class, "index"])->middleware("can:viewAny,App\Models\Product");
    public function test_index_withData_returnsProducts()  
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



  //    Route::get("/{product}", [ProductController::class, "show"])->middleware("can:view,product");
 public function test_show_withData_returnsProductBySpecificID(){
      $product=Product::factory()->create();
        $user = User::factory()->create();
        $token = $user->createToken('YourAppName')->plainTextToken; // Get the API token


        $response = $this->get('/api/products/'. $product->id, [
            'Authorization' => 'Bearer ' . $token,  // Pass the Bearer token in the request
      
        ]);
         $response->assertStatus(200);
           $response->assertJsonStructure([
            '*' => ['id', 'name', 'description', 'price', 'stock_quantity','created_at',"updated_at"] 
            
        ]);

 }





 // Route::put("/{product}", [ProductController::class, "update"])->middleware("can:update,product");
    public function test_update_withData_updatesProductBySpecificID()
    {
        $product = Product::factory()->create();
         $user = User::factory()->create();
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
            'Authorization' => 'Bearer ' . $token,  // Pass the Bearer token in the request
        //    'Accept' => 'application/json',         // Ensure the response is JSON
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






}


//