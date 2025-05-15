<?php
// app/Http/Controllers/Backend/ProductController.php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductServiceInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Add this import
use Illuminate\Http\Request; // ????????????????????
 // For validation
             // For route model binding
  

class ProductController extends Controller
{
    use AuthorizesRequests; // Include the trait here

    protected $productService;

    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = $this->productService->getAllProducts();
        return response()->json($products);
    }

    public function store(ProductRequest $request)
    {
        $validatedData = $request->validated();  // Validate the request data
        $product = $this->productService->createProduct($validatedData);  // Create the product
        return response()->json($product, 201);  // Return the created product with a 201 status
    }

    public function show(Product $product) // Use route model binding here
    {
        // The $product instance is now automatically injected by Laravel if found.
        // If not found, Laravel automatically returns a 404.
        $this->authorize("view", $product);
        return response()->json($product);
    }
    
   //
  // In app/Http/Controllers/Backend/ProductController.php

// Ensure these are at the top of your file if not already present:
      // As a fallback if not using ProductRequest for some reason

// ... other methods ...

public function update(ProductRequest $request, Product $product) // Using ProductRequest for validation
{
    // 1. Authorize the action (optional if you handle this with route middleware, but good for explicitness)
    $this->authorize("update", $product);

    // 2. Get validated data from the request
    $validatedData = $request->validated();

    // 3. Call your service to update the product
    // The $product->id comes from the route model bound $product instance
    $updatedProduct = $this->productService->updateProduct($product->id, $validatedData);

    // 4. Check if the product was successfully updated by the service
    if ($updatedProduct) {
        return response()->json($updatedProduct);
    } else {
        // This case might occur if your service returns null when a product isn't found,
        // though route model binding should typically handle 404s before this.
        // However, it's good practice if your service *could* return null for other reasons.
        return response()->json(["message" => "Product could not be updated or was not found by the service"], 404); 
    }
}


     // Make sure this is imported

//public function update(Request $request, Product $product) 
//{
 //   return response()->json(["message" => "Product received in controller for updatess", "product" => $product]);
//}

    




    
   public function destroy(Product $product) // <-- Changed $id to Product $product
{
    // The $product instance is now automatically injected by Laravel.
    // If not found, Laravel automatically returns a 404 before this method is called.
    
    // The route middleware "can:delete,product" will already have authorized this.
    // However, if you prefer explicit authorization in the controller as a double-check
    // or if you remove the route middleware, you can keep this line:
    // $this->authorize("delete", $product);
    // If the route middleware handles it, this line in the controller might be redundant
    // but doesn't harm (it will just run the policy check again).

    $this->productService->deleteProduct($product->id);

    return response()->json(["message" => "Product deleted successfully"]);
}

}