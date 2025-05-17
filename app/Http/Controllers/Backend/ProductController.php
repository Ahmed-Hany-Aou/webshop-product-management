<?php
// app/Http/Controllers/Backend/ProductController.php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductServiceInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Add this import


/**
 * @OA\Tag(
 *     name="Products",
 *     description="API Endpoints for product management"
 * )
 */
  

class ProductController extends Controller
{
    use AuthorizesRequests; // Include the trait here

    protected $productService;

    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }


    
    /**
     * List all products
     * 
     * @OA\Get(
     *     path="/products",
     *     operationId="getProducts",
     *     tags={"Products"},
     *     summary="Get all products",
     *     description="Returns a list of all products",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1, description="Auto-generated product ID"),
     *                 @OA\Property(property="name", type="string", example="Product Name", description="Product name"),
     *                 @OA\Property(property="description", type="string", example="Product description", description="Product description"),
     *                 @OA\Property(property="price", type="number", format="float", example=99.99, description="Product price"),
     *                 @OA\Property(property="stock_quantity", type="integer", example=100, description="Available stock quantity"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T12:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="This action is unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Too Many Requests",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Too Many Requests")
     *         )
     *     )
     * )
     */


    public function index()
    {
        $products = $this->productService->getAllProducts();
        return response()->json($products);
    }


     /**
     * Create a new product
     * 
     * @OA\Post(
     *     path="/products",
     *     operationId="storeProduct",
     *     tags={"Products"},
     *     summary="Create a new product",
     *     description="Creates a new product and returns the created product data",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","price","stock_quantity"},
     *             @OA\Property(property="name", type="string", example="New Product", description="Product name (required)"),
     *             @OA\Property(property="description", type="string", example="Product description", description="Product description (optional)"),
     *             @OA\Property(property="price", type="number", format="float", example=99.99, description="Product price (required)"),
     *             @OA\Property(property="stock_quantity", type="integer", example=100, description="Available stock quantity (required)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1, description="Auto-generated product ID"),
     *             @OA\Property(property="name", type="string", example="New Product", description="Product name"),
     *             @OA\Property(property="description", type="string", example="Product description", description="Product description"),
     *             @OA\Property(property="price", type="number", format="float", example=99.99, description="Product price"),
     *             @OA\Property(property="stock_quantity", type="integer", example=100, description="Available stock quantity"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="This action is unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="array",
     *                     @OA\Items(type="string", example="The name field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Too Many Requests",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Too Many Requests")
     *         )
     *     )
     * )
     */



    public function store(ProductRequest $request)
    {
        $validatedData = $request->validated();  // Validate the request data
        $product = $this->productService->createProduct($validatedData);  // Create the product
        return response()->json($product, 201);  // Return the created product with a 201 status
    }


     /**
     * Get a specific product
     * 
     * @OA\Get(
     *     path="/products/{product}",
     *     operationId="getProduct",
     *     tags={"Products"},
     *     summary="Get a specific product",
     *     description="Returns a specific product by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1, description="Auto-generated product ID"),
     *             @OA\Property(property="name", type="string", example="Product Name", description="Product name"),
     *             @OA\Property(property="description", type="string", example="Product description", description="Product description"),
     *             @OA\Property(property="price", type="number", format="float", example=99.99, description="Product price"),
     *             @OA\Property(property="stock_quantity", type="integer", example=100, description="Available stock quantity"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="This action is unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Product] 1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Too Many Requests",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Too Many Requests")
     *         )
     *     )
     * )
     */





    public function show(Product $product) // Use route model binding here
    {
        // The $product instance is now automatically injected by Laravel if found.
        // If not found, Laravel automatically returns a 404.
        $this->authorize("view", $product);
        return response()->json($product);
    }
    
   /**
     * Update a product
     * 
     * @OA\Put(
     *     path="/products/{product}",
     *     operationId="updateProduct",
     *     tags={"Products"},
     *     summary="Update a product",
     *     description="Updates a product and returns the updated product data",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","price","stock_quantity"},
     *             @OA\Property(property="name", type="string", example="Updated Product", description="Product name (required)"),
     *             @OA\Property(property="description", type="string", example="Updated description", description="Product description (optional)"),
     *             @OA\Property(property="price", type="number", format="float", example=149.99, description="Product price (required)"),
     *             @OA\Property(property="stock_quantity", type="integer", example=50, description="Available stock quantity (required)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1, description="Auto-generated product ID"),
     *             @OA\Property(property="name", type="string", example="Updated Product", description="Product name"),
     *             @OA\Property(property="description", type="string", example="Updated description", description="Product description"),
     *             @OA\Property(property="price", type="number", format="float", example=149.99, description="Product price"),
     *             @OA\Property(property="stock_quantity", type="integer", example=50, description="Available stock quantity"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="This action is unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Product] 1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="price",
     *                     type="array",
     *                     @OA\Items(type="string", example="The price must be at least 0.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Too Many Requests",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Too Many Requests")
     *         )
     *     )
     * )
     */

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


/**
     * Delete a product
     * 
     * @OA\Delete(
     *     path="/products/{product}",
     *     operationId="deleteProduct",
     *     tags={"Products"},
     *     summary="Delete a product",
     *     description="Deletes a product",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="This action is unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Product] 1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Too Many Requests",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Too Many Requests")
     *         )
     *     )
     * )
     */


    
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