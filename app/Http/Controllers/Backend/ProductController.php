<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Product;
use App\Services\ProductServiceInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Products",
 *     description="API Endpoints for product management"
 * )
 */
class ProductController extends Controller
{
    use AuthorizesRequests;

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
     *     description="Returns a paginated list of all products",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Products retrieved successfully"),
     *             @OA\Property(
     *                 property="result",
     *                 type="object",
     *                 @OA\Property(
     *                     property="items",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1, description="Auto-generated product ID"),
     *                         @OA\Property(property="name", type="string", example="Product Name", description="Product name"),
     *                         @OA\Property(property="description", type="string", example="Product description", description="Product description"),
     *                         @OA\Property(property="price", type="number", format="float", example=99.99, description="Product price"),
     *                         @OA\Property(property="stock_quantity", type="integer", example=100, description="Available stock quantity"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T12:00:00Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T12:00:00Z")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="meta",
     *                     type="object",
     *                     @OA\Property(property="page", type="integer", example=1),
     *                     @OA\Property(property="take", type="integer", example=10),
     *                     @OA\Property(property="items_count", type="integer", example=10),
     *                     @OA\Property(property="total_items_count", type="integer", example=100),
     *                     @OA\Property(property="page_count", type="integer", example=10),
     *                     @OA\Property(property="has_previous_page", type="boolean", example=false),
     *                     @OA\Property(property="has_next_page", type="boolean", example=true)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=401),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="result", type="null", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=403),
     *             @OA\Property(property="message", type="string", example="This action is unauthorized"),
     *             @OA\Property(property="result", type="null", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Too Many Requests",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=429),
     *             @OA\Property(property="message", type="string", example="Too Many Requests"),
     *             @OA\Property(property="result", type="null", example=null)
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        // Get pagination parameters from request or use defaults
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);
        
        // Get paginated products
        $products = $this->productService->getPaginatedProducts($page, $perPage);
        
        // Return standardized paginated response
        return ApiResponse::paginate($products, 'Products retrieved successfully');
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
     *             @OA\Property(property="status_code", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="Product created successfully"),
     *             @OA\Property(
     *                 property="result",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1, description="Auto-generated product ID"),
     *                 @OA\Property(property="name", type="string", example="New Product", description="Product name"),
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
     *             @OA\Property(property="status_code", type="integer", example=401),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="result", type="null", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=403),
     *             @OA\Property(property="message", type="string", example="This action is unauthorized"),
     *             @OA\Property(property="result", type="null", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=422),
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="result",
     *                 type="object",
     *                 @OA\Property(
     *                     property="errors",
     *                     type="object",
     *                     @OA\Property(
     *                         property="name",
     *                         type="array",
     *                         @OA\Items(type="string", example="The name field is required.")
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function store(ProductRequest $request)
    {
        $validatedData = $request->validated();
        $product = $this->productService->createProduct($validatedData);
        
        return ApiResponse::success($product, 'Product created successfully', 201);
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
     *             @OA\Property(property="status_code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Product retrieved successfully"),
     *             @OA\Property(
     *                 property="result",
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
     *             @OA\Property(property="status_code", type="integer", example=401),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="result", type="null", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=403),
     *             @OA\Property(property="message", type="string", example="This action is unauthorized"),
     *             @OA\Property(property="result", type="null", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Product not found"),
     *             @OA\Property(property="result", type="null", example=null)
     *         )
     *     )
     * )
     */
    public function show(Product $product)
    {
        $this->authorize("view", $product);
        return ApiResponse::success($product, 'Product retrieved successfully');
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
     *             @OA\Property(property="status_code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Product updated successfully"),
     *             @OA\Property(
     *                 property="result",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1, description="Auto-generated product ID"),
     *                 @OA\Property(property="name", type="string", example="Updated Product", description="Product name"),
     *                 @OA\Property(property="description", type="string", example="Updated description", description="Product description"),
     *                 @OA\Property(property="price", type="number", format="float", example=149.99, description="Product price"),
     *                 @OA\Property(property="stock_quantity", type="integer", example=50, description="Available stock quantity"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T12:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=401),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="result", type="null", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=403),
     *             @OA\Property(property="message", type="string", example="This action is unauthorized"),
     *             @OA\Property(property="result", type="null", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Product not found"),
     *             @OA\Property(property="result", type="null", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=422),
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="result",
     *                 type="object",
     *                 @OA\Property(
     *                     property="errors",
     *                     type="object",
     *                     @OA\Property(
     *                         property="price",
     *                         type="array",
     *                         @OA\Items(type="string", example="The price must be at least 0.")
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function update(ProductRequest $request, Product $product)
    {
        $this->authorize("update", $product);
        $validatedData = $request->validated();
        $updatedProduct = $this->productService->updateProduct($product->id, $validatedData);

        if ($updatedProduct) {
            return ApiResponse::success($updatedProduct, 'Product updated successfully');
        } else {
            return ApiResponse::error('Product could not be updated', 404);
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
     *             @OA\Property(property="status_code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Product deleted successfully"),
     *             @OA\Property(property="result", type="null", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=401),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="result", type="null", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=403),
     *             @OA\Property(property="message", type="string", example="This action is unauthorized"),
     *             @OA\Property(property="result", type="null", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Product not found"),
     *             @OA\Property(property="result", type="null", example=null)
     *         )
     *     )
     * )
     */
    public function destroy(Product $product)
    {
        $this->productService->deleteProduct($product->id);
        return ApiResponse::success(null, 'Product deleted successfully');
    }
}
