<?php

use App\Http\Controllers\Backend\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('products')->group(function() {
    // Get All products
    Route::get('/', [ProductController::class, 'index'])->name('products.index');// Get all products
    Route::get('/{id}', [ProductController::class, 'show'])->name('products.show'); // Get a single product by ID
    Route::post('/', [ProductController::class, 'store'])->name('products.store'); // Create a new product
    Route::put('/{id}', [ProductController::class, 'update'])->name('products.update');// Update a product
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('products.destroy');// Delete a product
});