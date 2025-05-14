<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/categories', [ProductController::class, 'getCategories']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Sales routes - temporarily remove auth middleware for testing
Route::post('/sales/process', [SaleController::class, 'processSale']);
Route::get('/products/{id}/stock', [ProductController::class, 'getStock']); 