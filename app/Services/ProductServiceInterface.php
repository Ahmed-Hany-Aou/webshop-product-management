<?php

namespace App\Services;


/// to apply soild principles 
//interface  will handle product crud actions
interface ProductServiceInterface
{
    public function getAllProducts();

    public function getProductsByID($id);

    public function updateProduct($id, $validatedData);

    public function createProduct($validatedData);
    public function deleteProduct($id);
}

