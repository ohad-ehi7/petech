<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SalesReceiptController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('test', function () {
    return view('test');
});


Route::get('home', function () {
    return view('home');
});

Route::get('product-list', function () {
    return view('product-list');
});

Route::get('new-item', function () {
    return view('new-item');
});

Route::get('product-overview', function () {
    return view('product-overview');
});

Route::get('product-transaction', function () {
    return view('product-transaction');
});

Route::get('point-of-sale', function () {
    return view('point-of-sale');
});

Route::get('card-type', function () {
    return view('card-type');
});

Route::get('sales-transaction', function () {
    return view('sales-transaction');
});

Route::get('/sales-receipt/{id}/pdf', [SalesReceiptController::class, 'generatePDF'])->name('sales-receipt.pdf');

Route::get('sales-receipt', function () {
    // For testing, let's get the first sale
    $sale = \App\Models\Sale::with(['customer', 'salesItems.product'])->first();
    return view('sales-receipt', ['sale' => $sale]);
});

// Settings Routes
Route::get('/settings', function () {
    return view('settings');
})->name('settings');

Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

// Authentication Routes
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    //TO BE ADDED: Middlware, might be annoying sa developer kay sigeg login2 every time na mag access HAHAH

});

