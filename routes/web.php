<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InventoryLogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Home Routes
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Authentication Routes
Route::get('/landing', [AuthController::class, 'index'])->name('login');
Route::post('/landing', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

// Product Routes
    Route::get('/product-list', [ProductController::class, 'index'])->name('products.index');
    Route::get('/new-item', [ProductController::class, 'create'])->name('products.create');
    Route::post('/new-item', [ProductController::class, 'store'])->name('products.store');
    Route::get('/product-overview/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/product-overview/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/product-overview/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/product-overview/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/product-transaction/', [ProductController::class, 'inventoryStatus'])->name('products.transaction');

// Supplier Routes
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

// Category Routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

// Sales Routes
    Route::get('/point-of-sale', [POSController::class, 'index'])->name('pos.index');

    Route::get('/sales-transaction', [SaleController::class, 'index'])->name('sales.transaction');

    Route::get('/sales-receipt', function () {
        return view('sales-receipt');
    })->name('receipt');

// Settings Routes
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');

// Purchase Routes


    Route::get('/suppliers/purchase-product', function () {
        return view('purchase-product');
    })->name('suppliers.purchase.product');

    Route::get('suppliers/purchase-billing', function () {
        return view('purchase-billing');
    })->name('purchase.billing');

    Route::get('/suppliers/purchase-invoice', function () {
        return view('purchase-invoice');
    })->name('suppliers.purchase.invoice');

// Sales routes
Route::post('/sales/process', [SaleController::class, 'processSale']);

Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
Route::delete('/sales/bulk-delete', [SaleController::class, 'bulkDelete'])->name('sales.bulk-delete');

//Reports Routes

    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');

    Route::get('/inventory-logs', [InventoryLogController::class, 'index'])->name('inventory.logs');

});

