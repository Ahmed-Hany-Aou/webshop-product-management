<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProductServiceInterface;

use App\Http\Requests\ProductRequest;


class ProductController extends Controller
{
    protected $productService;

    // Inject (use) the ProductServiceInterface via the constructor
    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all products using the service
        $products = $this->productService->getAllProducts();

        // Return a JSON response with the products
        return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

       // Handle creating a product
    public function store(ProductRequest $request)
    {
        // The request will be validated automatically at this point
        $validatedData = $request->validated();

        // Use the validated data to create a product using the service
        $product = $this->productService->createProduct($validatedData);

        return response()->json($product, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $products = $this->productService->getProductsByID($id);
        if (!$products) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($products);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */


         // Handle updating a product
    public function update(ProductRequest $request, $id)
    {
        // The request will be validated automatically at this point
        $validatedData = $request->validated();

        $product = $this->productService->updateProduct($id, $validatedData);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deleted = $this->productService->deleteProduct($id);
        if (!$deleted) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json(['message' => 'Product deleted successfully']);
    }

}
