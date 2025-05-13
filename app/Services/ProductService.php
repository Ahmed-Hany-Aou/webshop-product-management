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
}
