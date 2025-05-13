<?php

use App\Http\Controllers\Backend\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('products')->group(function() {
    // Get All products
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
});