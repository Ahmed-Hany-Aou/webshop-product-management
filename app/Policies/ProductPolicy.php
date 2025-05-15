<?php
// app/Policies/ProductPolicy.php

namespace App\Policies;

use App\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log; // Make sure to add this at the top if not already there


class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any products.
     */
    public function viewAny($user): bool
    {
        return true; // Allow all authenticated users to view products
      // return $user->hasRole('user'); 
    }

    /**
     * Determine if the user can view a specific product.
     */
    public function view($user, Product $product)
    {
        return $user->hasRole('admin'); // Only admins can view a product
    }

    /**
     * Determine if the user can create a product.
     */
    public function create($user)
    {
        return $user->hasRole('admin'); // Only admins can create products
    }

    /**
     * Determine if the user can update a product.
     */
    public function update($user, Product $product)
    {
        return $user->hasRole("admin");
     
    }
    
    /**
     * Determine if the user can delete a product.
     */
    public function delete($user, Product $product)
    {
        return $user->hasRole('admin'); // Only admins can delete products
    }
}
