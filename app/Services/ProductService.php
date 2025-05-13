<?php

namespace App\Services;

use App\Models\Product;

//implementation of the ProductServiceInterface/////

class ProductService implements ProductServiceInterface
{
    public function getAllProducts()
    {
        // This method gets all the products
        return Product::all();  
    }
    public function getProductsByID($id){

        // this method returns product by specific ID
        return Product::find($id); 
    }

    public function updateProduct($id, $validatedData){
        // This method updates a product by ID
        $product = Product::find($id);
        if ($product) {
            $product->update($validatedData);
            return $product;
        }
        return null; // or throw an exception if not found


    }

    public function createProduct($validatedData){
        // This method creates a new product
        return Product::create($validatedData);

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


