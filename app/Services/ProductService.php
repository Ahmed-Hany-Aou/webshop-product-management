<?php

namespace App\Services;

use App\Models\Product;

//implementation of the ProductServiceInterface/////

class ProductService implements ProductServiceInterface
{

    protected $priceAdjustmentService;

    public function __construct(PriceAdjustmentService $priceAdjustmentService)
    {
        $this->priceAdjustmentService = $priceAdjustmentService;
    }





    public function getAllProducts()
    {
        // This method gets all the products
        return Product::all();  
    }
    public function getProductsByID($id){

        // this method returns product by specific ID
        return Product::find($id); 
    }

    public function updateProduct($id, $validatedData)
    {
        $product = Product::find($id);
        if ($product) {
            $product->update($validatedData);
            // Adjust the price based on stock level
            $product = $this->priceAdjustmentService->adjustPrice($product);
            $product->save();  // Save the adjusted price
            return $product;
           
        }
        return null;
    }

    public function createProduct($validatedData)
    {
        $product = Product::create($validatedData);
        // Adjust the price based on stock level
        $product = $this->priceAdjustmentService->adjustPrice($product);
        $product->save();  // Save the adjusted price
       return $product;
    }
    public function deleteProduct($id){
        // This method deletes a product by ID
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            return true;
        }
        return false; // or throw an exception if not found
    }
}


