<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Backend\ProductController;



// Authentication Routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
// routes/api.php



Route::middleware(["auth:sanctum", "throttle:10,1"])->prefix("products")->group(function () {
    Route::get("/", [ProductController::class, "index"])->middleware("can:viewAny,App\Models\Product");
    Route::get("/{product}", [ProductController::class, "show"])->middleware("can:view,product");

                                                                                                    // However, for consistency with update/delete, you might consider changing this to {product} and can:view,product too.
    Route::post("/", [ProductController::class, "store"])->middleware("can:create,App\Models\Product"); // This was commented out in your file, uncomment if needed.
    
    // THIS IS THE CORRECT ROUTE FOR UPDATE
   // Route::put("/{product}", [ProductController::class, "update"])->middleware("can:update,product");
   //
   // 
  Route::put("/{product}", [ProductController::class, "update"]);

 //Route::patch("/{product}", [ProductController::class, "update"]); 

  
  


    // REMOVE OR COMMENT OUT THIS CONFLICTING ROUTE
    // Route::put("/{id}", [ProductController::class, "update"])->middleware("can:update,App\Models\Product"); 

    //Route::delete("/{id}", [ProductController::class, "destroy"])->middleware("can:delete,App\Models\Product"); // Similar to show, consider {product} and can:delete,product for consistency.
   //   Route::delete("/{id}", [ProductController::class, "destroy"])->middleware("can:delete,App\Models\Product");
      Route::delete("/{product}", [ProductController::class, "destroy"])->middleware("can:delete,product");


});

// User Info Route (authenticated)
Route::middleware(['auth:sanctum'])->get('user', [AuthController::class, 'user']);