<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/categories', [ProductController::class, 'getCategories']); 