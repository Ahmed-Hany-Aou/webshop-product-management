<?php
/*

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\ProductController;

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;

// Authentication Routes
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
// Authentication Routes




// Protected Product Routes (require authentication)
Route::middleware('auth:sanctum')->prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::post('/', [ProductController::class, 'store']);
    Route::put('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'destroy']);
});

// User Info Route
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
