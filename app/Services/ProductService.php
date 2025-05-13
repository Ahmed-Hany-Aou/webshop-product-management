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
}
